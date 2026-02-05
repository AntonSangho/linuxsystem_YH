---
name: publish
description: 콘텐츠 파일 변경사항을 git commit & push
disable-model-invocation: true
allowed-tools: Bash
---

# Publish Changes

1. `git status`를 실행하여 변경된 파일 목록을 보여준다
2. 변경 내용 요약을 사용자에게 보여준다
3. 커밋 메시지를 제안하고 사용자에게 확인받는다 (형식: `Day X: 내용 설명`)
4. 승인 후 `git add` → `git commit -m "<메시지>"` → `git push` 실행
5. 성공 여부를 확인하고 결과를 보여준다
