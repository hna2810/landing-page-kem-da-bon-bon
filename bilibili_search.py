import urllib.request
import urllib.parse
import json
import sys
import datetime

sys.stdout.reconfigure(encoding='utf-8')

keywords = ['母婴好物', '沉浸式带娃', '待产包', '带娃神器', 'AI萌娃', '人类幼崽', '宝宝辅食']

for kw in keywords:
    url = f"https://api.bilibili.com/x/web-interface/search/type?search_type=video&keyword={urllib.parse.quote(kw)}&order=click"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            vlist = data.get('data', {}).get('result', [])
            print(f"\n=== KEYWORD: {kw} (Found: {len(vlist)}) ===")
            for v in vlist[:8]:
                play = v.get('play')
                pubdate = v.get('pubdate', 0)
                bvid = v.get('bvid')
                title = v.get('title', '').replace('<em class="keyword">', '').replace('</em>', '')
                dt = datetime.datetime.fromtimestamp(pubdate)
                print(f"https://www.bilibili.com/video/{bvid} | Views: {play} | Date: {dt.strftime('%Y-%m-%d')} | {title[:50]}")
    except Exception as e:
        print(f"Error {kw}:", e)
