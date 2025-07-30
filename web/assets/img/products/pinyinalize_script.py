#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
from pypinyin import lazy_pinyin

# 支持的图片后缀（小写）
EXTS = {'.webp', '.jpg', '.png'}

def to_pinyin(name: str) -> str:
    """
    把 name 中的中文字符转换为拼音，ascii 字符保留不变，
    最终拼接成一整个字符串。
    """
    return ''.join(lazy_pinyin(name))

def main():
    for fname in os.listdir('.'):
        base, ext = os.path.splitext(fname)
        if ext.lower() in EXTS:
            new_base = to_pinyin(base)
            new_name = new_base + ext.lower()
            if fname == new_name:
                continue  # 名字已经是拼音，跳过
            if os.path.exists(new_name):
                print(f"⚠️ 跳过重命名：目标文件已存在 —— {new_name}")
            else:
                try:
                    os.rename(fname, new_name)
                    print(f"✅ {fname} → {new_name}")
                except Exception as e:
                    print(f"❌ 重命名失败 {fname}：{e}")

if __name__ == '__main__':
    main()
