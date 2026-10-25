#!/usr/bin/env python3
"""Convert the WordPress export (WXR) of the old hrideone.com into playground/data/blog-posts.json.

The old site runs WPML + Elementor. The export has no language or translation data, so:
- language comes from the title script (Thai / Japanese / otherwise English),
- the TH / EN / JA versions of one article share the same post_date (WPML copies it): 115 groups x 3,
- the post HTML (Elementor output) is cleaned and rewritten as core blocks (paragraph, heading, list,
  image, quote, table, separator, details), so the block editor shows no Classic block,
- categories: the real Business / Sales / Coaching are kept; posts that only had "ไม่มีหมวดหมู่"
  (uncategorized) get one of those three from CATEGORY_MAP below (decided by title; marketing should review).

Usage: python3 -I scripts/wxr-to-blog.py <export.xml> [out.json]
Needs: beautifulsoup4 (pip install beautifulsoup4)
"""

import collections
import gzip
import json
import re
import sys
import xml.etree.ElementTree as ET
from html import escape

from bs4 import BeautifulSoup, Comment, NavigableString, Tag

NS = {
    'wp': 'http://wordpress.org/export/1.2/',
    'content': 'http://purl.org/rss/1.0/modules/content/',
    'excerpt': 'http://wordpress.org/export/1.2/excerpt/',
}
SITE = 'https://hrideone.com'
CATEGORIES = ('business', 'sales', 'coaching')

# Thai slug -> category for posts that had no real category on the old site (applies to the EN / JA versions too).
# business = organization, culture, strategy, HR / talent, company news · sales = selling, negotiation
# coaching = leadership, managers, self-development, working with people
CATEGORY_MAP = {
    'why-you-must-training-with-deone-academy': 'business',
    'how-to-chage-mindset-impacts-your-business': 'business',
    '7-reasons-why-communication-failure': 'business',
    '5-processes-to-reskill-employees': 'business',
    '3-netflix-strategies-for-managing-people': 'business',
    'cross-functional-management-cfm': 'business',
    '4-principles-to-creating-positive-change-at-work': 'business',
    'how-agility-important-to-organization-culture': 'business',
    'grid-framework-culture-transformation-tools': 'business',
    'people-transformation-with-green-yellow-red-idea': 'business',
    '5-reasons-why-organization-need-a-culture': 'business',
    '8-sign-of-toxic-workplace': 'business',
    'what-kind-of-great-way-of-working': 'business',
    '5-way-to-retain-talented-people-in-organization': 'business',
    'understanding-co-worker-style-improve-culture': 'business',
    'challenges-of-culture-in-the-new-era-organization': 'business',
    'professional-manager-can-create-different-results': 'business',
    '4-tips-to-solve-silo-problem': 'business',
    '5-ways-to-avoid-employees-feeling-alienated': 'business',
    'hri-deone-launch-event-develop-leaders': 'business',
    'catch-liar-from-9-behaviors': 'sales',
    'unlock-data-power-for-superior-sales-strategy': 'sales',
    '3-strategies-to-win-hearts-of-subordinates': 'coaching',
    'what-skills-should-a-successful-manager-have': 'coaching',
    '7-roles-of-manager-to-make-team-effective': 'coaching',
    'why-managers-need-to-take-a-leadership-course': 'coaching',
    'time-management-technique-of-professional-manager': 'coaching',
    '5-steps-to-build-self-leadership': 'coaching',
    '5-reasons-why-good-leaders-need-to-listen': 'coaching',
    '5-different-perspectives-employee-and-supervisor': 'coaching',
    'vak-model-learning-styles': 'coaching',
    'why-people-quit-their-jobs': 'coaching',
    'learn-unlearn-relearn-3-things-need-to-focus': 'coaching',
    '5-soft-skills-survive-in-the-disruption-era': 'coaching',
    'boss-vs-leader-similar-but-different': 'coaching',
    '7-solutions-to-solve-the-most-popular-problem': 'coaching',
    '5-types-of-tasks-that-manager-dont-do': 'coaching',
    '5-ways-to-set-work-priority-for-manager': 'coaching',
    '5-things-to-do-after-meeting-is-over': 'coaching',
    'difference-proactive-leader-reactive-leader': 'coaching',
    '4-difference-between-feedback-and-complaint': 'coaching',
    '6-habits-awaken-your-growth-mindset': 'coaching',
    'leadership-lesson-from-10-legendary-leaders-quote': 'coaching',
    'get-to-know-niksen-no-more-work-all-the-time': 'coaching',
    '5-ways-effectively-delegate-tasks': 'coaching',
    '5-idea-servant-leadership-in-starbucks': 'coaching',
    '3-trick-how-to-ask-for-help-at-work': 'coaching',
    'how-to-work-with-introvert-extrovert': 'coaching',
    'qualities-of-a-good-role-model-in-the-workplace': 'coaching',
    '12-best-words-of-appreciation-for-employees': 'coaching',
    '4-types-of-disc-leaders-and-their-pros-and-cons': 'coaching',
    'elon-musk-time-management-in-1-day': 'coaching',
    '4-ways-to-build-resilience-when-faced-obstacles': 'coaching',
    '5-techniques-for-working-with-micromanager': 'coaching',
    'why-leadership-training-are-necessary-for-manager': 'coaching',
    '3-advantage-when-you-attending-training': 'coaching',
    'too-kind-boss-is-silent-danger-in-organization': 'coaching',
    '7-ways-to-change-yourself-to-share-your-opinion': 'coaching',
    'why-do-managers-need-to-develop-leadership-skills': 'coaching',
    'why-leadership-remains-human-in-the-age-of-ai': 'coaching',
}

INLINE = {'strong', 'em', 'a', 'br', 'sub', 'sup', 'code'}
RENAME = {'b': 'strong', 'i': 'em'}
BLOCK = {'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'blockquote', 'table', 'figure', 'img', 'hr', 'div', 'section', 'article', 'header', 'footer', 'details'}
DROP = {'style', 'script', 'svg', 'noscript', 'iframe', 'form', 'input', 'button'}


def lang_of(title: str) -> str:
    if re.search(r'[฀-๿]', title):
        return 'th'
    if re.search(r'[぀-ヿ一-鿿]', title):
        return 'ja'
    return 'en'


def local_url(url: str) -> str:
    """Links to the old site become root-relative (they keep working on staging and after the move)."""
    url = (url or '').strip()
    for prefix in (SITE, 'http://hrideone.com', 'https://www.hrideone.com', 'http://www.hrideone.com'):
        if url.startswith(prefix + '/') or url == prefix:
            path = url[len(prefix):] or '/'
            return path
    return url


def inline_html(node) -> str:
    """Inline content of a node: only strong/em/a/br/sub/sup/code, no attributes except link ones."""
    out = []
    for child in node.children:
        if isinstance(child, Comment):
            continue
        if isinstance(child, NavigableString):
            out.append(escape(re.sub(r'\s+', ' ', str(child)), quote=False))
            continue
        if not isinstance(child, Tag) or child.name in DROP or child.name == 'img':
            continue
        name = RENAME.get(child.name, child.name)
        inner = inline_html(child)
        if name == 'br':
            out.append('<br>')
        elif name == 'a':
            href = child.get('href')
            if not href:
                out.append(inner)
                continue
            attrs = ' href="' + escape(local_url(href)) + '"'
            if child.get('target') == '_blank' and not local_url(href).startswith('/'):
                attrs += ' target="_blank" rel="noreferrer noopener"'
            out.append(f'<a{attrs}>{inner}</a>' if inner.strip() else '')
        elif name in INLINE:
            out.append(f'<{name}>{inner}</{name}>' if inner.strip() else inner)
        else:
            out.append(inner)  # span, font, u, ... unwrapped
    html = ''.join(out)
    html = re.sub(r'<(strong|em)>(\s*)</\1>', r'\2', html)
    return html


def tidy(html: str) -> str:
    html = re.sub(r'\s+', ' ', html).strip()
    html = re.sub(r'^(<br>\s*)+|(\s*<br>)+$', '', html).strip()
    return html


def has_text(html: str) -> bool:
    return bool(re.sub(r'<[^>]+>|&nbsp;|\s|\xa0', '', html))


def block(name: str, attrs: dict | None, inner: str) -> str:
    a = (' ' + json.dumps(attrs, ensure_ascii=False, separators=(',', ':'))) if attrs else ''
    return f'<!-- wp:{name}{a} -->\n{inner}\n<!-- /wp:{name} -->'


def video_block(shortcode: str) -> str:
    """[video mp4="…" poster="…"][/video] → core/video block."""
    attrs = dict(re.findall(r'(\w+)="([^"]*)"', shortcode))
    src = attrs.get('mp4') or attrs.get('src') or attrs.get('webm') or ''
    if not src:
        return ''
    poster = f' poster="{escape(attrs["poster"])}"' if attrs.get('poster') else ''
    return block('video', None, f'<figure class="wp-block-video"><video controls{poster} src="{escape(src)}"></video></figure>')


def paragraph(html: str, align: str = '') -> str:
    html = tidy(html)
    if not has_text(html):
        return ''
    if re.fullmatch(r'\[video [^\]]*\](?:\[/video\])?', html):
        return video_block(html)
    if align in ('center', 'right'):
        return block('paragraph', {'align': align}, f'<p class="has-text-align-{align}">{html}</p>')
    return block('paragraph', None, f'<p>{html}</p>')


def heading(level: int, html: str) -> str:
    html = tidy(html)
    # The heading is already bold: drop a <strong> that wraps all of it.
    m = re.fullmatch(r'<strong>(.*)</strong>', html)
    if m and '<strong>' not in m.group(1):
        html = m.group(1).strip()
    if not has_text(html):
        return ''
    level = min(max(level, 2), 6)
    attrs = {'level': level} if level != 2 else None
    return block('heading', attrs, f'<h{level} class="wp-block-heading">{html}</h{level}>')


def image(img: Tag, caption: str = '') -> str:
    src = img.get('src') or ''
    if not src or src.startswith('data:'):
        return ''
    src = src if src.startswith('http') else SITE + src
    alt = escape(img.get('alt') or '')
    cap = f'<figcaption class="wp-element-caption">{tidy(caption)}</figcaption>' if has_text(caption) else ''
    return block('image', None, f'<figure class="wp-block-image"><img src="{escape(src)}" alt="{alt}"/>{cap}</figure>')


def list_block(node: Tag) -> str:
    items = []
    for li in node.find_all('li', recursive=False):
        nested = ''.join(list_block(sub) for sub in li.find_all(['ul', 'ol'], recursive=False))
        for sub in li.find_all(['ul', 'ol'], recursive=False):
            sub.extract()
        text = tidy(inline_html(li))
        if not has_text(text) and not nested:
            continue
        items.append(block('list-item', None, f'<li>{text}{nested}</li>'))
    if not items:
        return ''
    ordered = node.name == 'ol'
    tag = 'ol' if ordered else 'ul'
    return block('list', {'ordered': True} if ordered else None, f'<{tag} class="wp-block-list">' + ''.join(items) + f'</{tag}>')


def cell_html(cell: Tag) -> str:
    parts = [tidy(inline_html(p)) for p in cell.find_all('p')] or [tidy(inline_html(cell))]
    return '<br>'.join(p for p in parts if has_text(p))


def table_block(node: Tag) -> str:
    rows_head, rows_body = [], []
    for tr in node.find_all('tr'):
        cells = [cell_html(c) for c in tr.find_all(['td', 'th'], recursive=False)]
        (rows_head if tr.find_parent('thead') else rows_body).append(cells)
    if not rows_body and rows_head:
        rows_body, rows_head = rows_head, []
    html = '<figure class="wp-block-table"><table class="has-fixed-layout">'
    if rows_head:
        html += '<thead>' + ''.join('<tr>' + ''.join(f'<th>{c}</th>' for c in r) + '</tr>' for r in rows_head) + '</thead>'
    html += '<tbody>' + ''.join('<tr>' + ''.join(f'<td>{c}</td>' for c in r) + '</tr>' for r in rows_body) + '</tbody>'
    html += '</table></figure>'
    return block('table', None, html)


def align_of(node: Tag) -> str:
    m = re.search(r'text-align:\s*(center|right)', node.get('style') or '')
    return m.group(1) if m else ''


class Converter:
    def __init__(self):
        self.blocks: list[str] = []
        self.buffer: list[str] = []  # loose inline content
        self.faq: list | None = None  # [question, [answer blocks]] while inside an Elementor toggle

    def emit(self, html: str):
        if not html:
            return
        if self.faq is not None:
            self.faq[1].append(html)
        else:
            self.blocks.append(html)

    def flush(self):
        if self.buffer:
            self.emit(paragraph(''.join(self.buffer)))
            self.buffer = []

    def close_faq(self):
        self.flush()
        if self.faq is not None:
            question, answer = self.faq
            self.faq = None
            if answer:
                self.blocks.append(
                    '<!-- wp:details {"className":"hri-faq__item"} -->\n<details class="wp-block-details hri-faq__item"><summary>'
                    + question + '</summary>' + ''.join(answer) + '</details>\n<!-- /wp:details -->'
                )

    def walk(self, node):
        for child in list(node.children):
            if isinstance(child, Comment):
                continue
            if isinstance(child, NavigableString):
                if str(child).strip():
                    self.buffer.append(escape(re.sub(r'\s+', ' ', str(child)), quote=False))
                continue
            if not isinstance(child, Tag) or child.name in DROP:
                continue
            name = child.name
            # Elementor toggle / accordion title: <a tabindex="0"> without href.
            if name == 'a' and child.get('tabindex') is not None and not child.get('href'):
                self.close_faq()
                self.faq = [tidy(inline_html(child)), []]
                continue
            if name not in BLOCK and not child.find(list(BLOCK)):
                self.buffer.append(inline_html(BeautifulSoup(f'<x>{child}</x>', 'html.parser').x))
                continue
            self.flush()
            if name == 'p':
                imgs = child.find_all('img')
                if imgs:
                    self.split_images(child)
                else:
                    self.emit(paragraph(inline_html(child), align_of(child)))
            elif re.fullmatch(r'h[1-6]', name):
                if self.faq is not None and name in ('h1', 'h2'):
                    self.close_faq()
                self.emit(heading(int(name[1]), inline_html(child)))
            elif name in ('ul', 'ol'):
                self.emit(list_block(child))
            elif name == 'img':
                self.emit(image(child))
            elif name == 'hr':
                self.close_faq()
                self.emit(block('separator', None, '<hr class="wp-block-separator has-alpha-channel-opacity"/>'))
            elif name == 'table':
                self.emit(table_block(child))
            elif name == 'figure':
                if child.find('table'):
                    self.emit(table_block(child.find('table')))
                elif child.find('img'):
                    cap = child.find('figcaption')
                    self.emit(image(child.find('img'), inline_html(cap) if cap else ''))
            elif name == 'blockquote':
                self.emit(self.quote(child))
            else:  # div / section / wrappers: walk inside
                self.walk(child)
        self.flush()

    def split_images(self, p: Tag):
        """A paragraph holding images: text before / between / after becomes paragraphs."""
        part = []
        for child in list(p.children):
            if isinstance(child, Tag) and (child.name == 'img' or child.find('img')):
                self.emit(paragraph(''.join(part), align_of(p)))
                part = []
                for img in [child] if child.name == 'img' else child.find_all('img'):
                    self.emit(image(img))
            else:
                part.append(inline_html(BeautifulSoup(f'<x>{child}</x>', 'html.parser').x) if isinstance(child, Tag) else escape(str(child), quote=False))
        self.emit(paragraph(''.join(part), align_of(p)))

    def quote(self, node: Tag) -> str:
        cite = node.find('cite')
        cite_html = tidy(inline_html(cite)) if cite else ''
        if cite:
            cite.extract()
        inner = Converter()
        inner.walk(node)
        if not inner.blocks:
            return ''
        html = '<blockquote class="wp-block-quote">' + ''.join(inner.blocks)
        if has_text(cite_html):
            html += f'<cite>{cite_html}</cite>'
        return block('quote', None, html + '</blockquote>')


def to_blocks(html: str) -> str:
    html = re.sub(r'<!--.*?-->', '', html or '', flags=re.S)
    soup = BeautifulSoup(html, 'html.parser')
    conv = Converter()
    conv.walk(soup)
    conv.close_faq()
    out = conv.blocks
    # Elementor left runs of separators; keep at most one in a row and none at the start / end.
    cleaned = []
    for b in out:
        if b.startswith('<!-- wp:separator') and (not cleaned or cleaned[-1].startswith('<!-- wp:separator')):
            continue
        cleaned.append(b)
    while cleaned and cleaned[-1].startswith('<!-- wp:separator'):
        cleaned.pop()
    return '\n\n'.join(cleaned)


def text_of(html: str) -> str:
    return re.sub(r'\s+', ' ', BeautifulSoup(html or '', 'html.parser').get_text(' ')).strip()


def main():
    src = sys.argv[1]
    out = sys.argv[2] if len(sys.argv) > 2 else 'playground/data/blog-posts.json'
    channel = ET.parse(src).getroot().find('channel')
    items = channel.findall('item')

    attachments = {}
    for it in items:
        if it.find('wp:post_type', NS).text == 'attachment':
            meta = {m.find('wp:meta_key', NS).text: m.find('wp:meta_value', NS).text for m in it.findall('wp:postmeta', NS)}
            attachments[it.find('wp:post_id', NS).text] = {
                'url': it.find('wp:attachment_url', NS).text,
                'alt': meta.get('_wp_attachment_image_alt') or '',
            }

    groups = collections.defaultdict(dict)
    for it in items:
        if it.find('wp:post_type', NS).text != 'post' or it.find('wp:status', NS).text != 'publish':
            continue
        meta = collections.defaultdict(list)
        for m in it.findall('wp:postmeta', NS):
            meta[m.find('wp:meta_key', NS).text].append(m.find('wp:meta_value', NS).text or '')
        title = (it.find('title').text or '').strip()
        lang = lang_of(title)
        date = it.find('wp:post_date', NS).text
        thumb = attachments.get((meta.get('_thumbnail_id') or [''])[0], {})
        excerpt = text_of(it.find('excerpt:encoded', NS).text) or text_of((meta.get('rank_math_description') or [''])[0])
        cats = [c.get('nicename') for c in it.findall('category') if c.get('domain') == 'category']
        slug = it.find('wp:post_name', NS).text
        if lang in groups[date]:
            sys.exit(f'Two {lang} posts share the date {date}')
        groups[date][lang] = {
            'slug': slug,
            'title': title,
            'excerpt': excerpt,
            'content': to_blocks(it.find('content:encoded', NS).text),
            'thumbnail': thumb.get('url', ''),
            'thumbnail_alt': thumb.get('alt', ''),
            'old_slugs': sorted({s for s in meta.get('_wp_old_slug', []) if s and s != slug}),
            'old_id': int(it.find('wp:post_id', NS).text),
            # WordPress ≥5.7 exports the last-modified time; older exports have none (= date).
            'modified': (it.find('wp:post_modified', NS).text if it.find('wp:post_modified', NS) is not None else '') or date,
            'categories': cats,
        }

    data = []
    for date in sorted(groups, reverse=True):
        g = groups[date]
        if sorted(g) != ['en', 'ja', 'th']:
            sys.exit(f'{date}: incomplete translation group {sorted(g)}')
        real = [c for c in g['th']['categories'] if c in CATEGORIES]
        category = real[0] if real else CATEGORY_MAP.get(g['th']['slug'])
        if not category:
            sys.exit(f"No category for {g['th']['slug']}: add it to CATEGORY_MAP")
        for v in g.values():
            v['old_categories'] = v.pop('categories')
        data.append({'date': date, 'category': category, 'category_from_old_site': bool(real), 'translations': {k: g[k] for k in ('th', 'en', 'ja')}})

    text = json.dumps({'source': 'hrideone.com WordPress export', 'categories': list(CATEGORIES), 'posts': data}, ensure_ascii=False, indent=1)
    with open(out, 'w', encoding='utf-8') as f:
        f.write(text)
    # Small copy for Tools → นำเข้าบทความ (fits the host's upload limit). mtime=0: same bytes on every run.
    with open(out + '.gz', 'wb') as f:
        f.write(gzip.compress(text.encode('utf-8'), mtime=0))
    counts = collections.Counter(p['category'] for p in data)
    print(f'{len(data)} articles x 3 languages -> {out} · ' + ', '.join(f'{k} {v}' for k, v in sorted(counts.items())))


if __name__ == '__main__':
    main()
