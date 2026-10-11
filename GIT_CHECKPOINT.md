git reset --hard clean-base-v1

# Git Checkpoint LENTERA

## 1. Clean Base
Tag:
clean-base-v1

Commit:
4e50ed1

Keterangan:
Base bersih sebelum redesign LENTERA.

Balik ke checkpoint:
git reset --hard clean-base-v1


## 2. Redesign LENTERA V1
Tag:
redesign-v1

Keterangan:
Redesign penuh LENTERA:
- tema dark forest green + cream
- branding LENTERA
- public pages
- user pages
- admin dashboard
- animasi UI
- layout responsive

Push redesign:
git add -A
git commit -m "feat: redesign LENTERA bookstore UI"
git push origin main

Setelah commit berhasil, buat tag:
git tag redesign-v1
git push origin redesign-v1


## Melihat checkpoint
git tag


## Melihat history
git log --oneline --decorate --graph


## Balik sementara ke clean base
git checkout clean-base-v1


## Balik main ke clean base
git reset --hard clean-base-v1


## Balik ke redesign
git reset --hard redesign-v1