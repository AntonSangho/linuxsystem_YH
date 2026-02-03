# Crontab 실습: 자동으로 시스템 관리하기

## 과제 개요

| 항목 | 내용 |
|------|------|
| 목표 | crontab으로 스크립트를 자동 실행하기 |
| 최종 결과물 | 1분마다 실행되는 시스템 모니터링 스크립트 |
| 실습 환경 | Ubuntu 22.04 (VMware) |
| 난이도 | ★★☆☆☆ (초급) |

---

## Crontab이란?

- 리눅스에서 **정해진 시간에 자동으로 명령어를 실행**하는 도구
- 예: 매일 새벽 3시에 백업, 1시간마다 로그 정리 등

### Cron 시간 형식

```
*    *    *    *    *    명령어
│    │    │    │    │
│    │    │    │    └── 요일 (0-7, 0과 7은 일요일)
│    │    │    └─────── 월 (1-12)
│    │    └──────────── 일 (1-31)
│    └───────────────── 시 (0-23)
└────────────────────── 분 (0-59)
```

### 자주 쓰는 예시

| 설정 | 의미 |
|------|------|
| `* * * * *` | 매 1분마다 |
| `0 * * * *` | 매시 정각마다 |
| `0 9 * * *` | 매일 오전 9시 |
| `0 0 * * 0` | 매주 일요일 자정 |
| `*/5 * * * *` | 5분마다 |

---

## Part 1: 실습 준비 (5분)

### 1-1. 작업 폴더 만들기

```bash
cd ~/projects
mkdir -p cron_lab/logs
cd cron_lab
pwd
```

**예상 결과:** `/home/user1/projects/cron_lab`

### 1-2. 모니터링 스크립트 만들기

```bash
cat << 'EOF' > monitor.sh
#!/bin/bash
# 시스템 모니터링 스크립트

# 현재 시간
echo "===== $(date) ====="

# 디스크 사용량 (/ 파티션만)
echo "[디스크]"
df -h / | tail -1

# 메모리 사용량
echo "[메모리]"
free -h | grep Mem

echo ""
EOF
```

### 1-3. 실행 권한 부여

```bash
chmod 755 monitor.sh
ls -l monitor.sh
```

### 1-4. 스크립트 테스트

```bash
./monitor.sh
```

**예상 결과:**
```
===== Mon Feb  3 10:30:00 KST 2026 =====
[디스크]
/dev/sda1        20G   5.2G   14G  28% /
[메모리]
Mem:           3.8Gi  1.2Gi  2.0Gi  ...
```

---

## Part 2: Crontab 기본 명령어 (5분)

```bash
# crontab 목록 보기
crontab -l

# crontab 편집하기
crontab -e

# crontab 삭제하기 (주의!)
# crontab -r
```

> **참고**: 처음 `crontab -e` 실행 시 에디터 선택 화면이 나오면 **nano (1번)** 선택

---

## Part 3: Crontab 등록하기 (10분)

### 3-1. Crontab 편집 열기

```bash
crontab -e
```

### 3-2. 맨 아래에 다음 줄 추가

```
* * * * * /home/user1/projects/cron_lab/monitor.sh >> /home/user1/projects/cron_lab/logs/system.log 2>&1
```

> **주의**: `user1` 부분을 본인의 사용자명으로 변경하세요.
> 확인 방법: `echo $USER`

### 3-3. 저장하고 나가기

- nano 에디터: `Ctrl + O` (저장) → `Enter` → `Ctrl + X` (나가기)

### 3-4. 등록 확인

```bash
crontab -l
```

**예상 결과:**
```
* * * * * /home/ubuntu/cron_lab/monitor.sh >> /home/ubuntu/cron_lab/logs/system.log 2>&1
```

---

## Part 4: 결과 확인 (5분)

### 4-1. 1~2분 기다린 후 로그 확인

```bash
cat ~/projects/cron_lab/logs/system.log
```

**예상 결과:**
```
===== Mon Feb  3 10:31:00 KST 2026 =====
[디스크]
/dev/sda1        20G   5.2G   14G  28% /
[메모리]
Mem:           3.8Gi  1.2Gi  2.0Gi  ...

===== Mon Feb  3 10:32:00 KST 2026 =====
[디스크]
/dev/sda1        20G   5.2G   14G  28% /
[메모리]
Mem:           3.8Gi  1.2Gi  2.0Gi  ...
```

### 4-2. 실시간 로그 확인

```bash
tail -f ~/projects/cron_lab/logs/system.log
```

> `Ctrl + C`로 종료

---

## Part 5: Crontab 삭제 (실습 후 정리)

### 5-1. 등록된 cron 삭제

```bash
crontab -e
```

추가했던 줄을 삭제하고 저장

### 5-2. 삭제 확인

```bash
crontab -l
```

---

## 제출 방법: Git으로 제출

### 1. Git 저장소 초기화

```bash
cd ~/projects/cron_lab
git init
```

### 2. 파일 추가 및 커밋

```bash
git add monitor.sh
git add logs/system.log
git commit -m "crontab 실습 완료"
```

### 3. GitHub에 Push

```bash
git remote add origin [본인의 GitHub 저장소 URL]
git branch -M main
git push -u origin main
```

### 제출 확인 사항

- [ ] `monitor.sh` 파일이 저장소에 있다
- [ ] `logs/system.log` 에 로그가 2개 이상 있다
- [ ] GitHub 저장소 URL 제출

---

## 평가 기준

| 항목 | 배점 |
|------|------|
| 스크립트 작성 | 30% |
| crontab 등록 | 40% |
| 로그 파일 생성 확인 | 30% |

---

## 자주 하는 실수

### 실수 1: 로그 파일이 안 생겨요

```bash
# 원인: 경로가 잘못됨
# 해결: 절대 경로 확인
echo $HOME
echo $USER
# 출력: /home/user1, user1
# crontab에 /home/user1/projects/cron_lab/... 사용
```

### 실수 2: crontab 편집이 안 돼요

```bash
# 해결: 에디터 재선택
select-editor
# 또는
export EDITOR=nano
crontab -e
```

### 실수 3: 스크립트가 실행 안 돼요

```bash
# 원인: 실행 권한 없음
chmod 755 ~/projects/cron_lab/monitor.sh

# 원인: 스크립트 경로 오류
ls -l ~/projects/cron_lab/monitor.sh
```

---

## 도전 과제 (선택)

### 도전 1: 5분마다 실행으로 변경

```
*/5 * * * * /home/user1/projects/cron_lab/monitor.sh >> /home/user1/projects/cron_lab/logs/system.log 2>&1
```

### 도전 2: 날짜별 로그 파일 생성

스크립트를 수정하여 날짜별 파일에 저장:

```bash
cat << 'EOF' > monitor.sh
#!/bin/bash
LOG_FILE=~/projects/cron_lab/logs/system_$(date +%Y%m%d).log

echo "===== $(date) =====" >> $LOG_FILE
echo "[디스크]" >> $LOG_FILE
df -h / | tail -1 >> $LOG_FILE
echo "[메모리]" >> $LOG_FILE
free -h | grep Mem >> $LOG_FILE
echo "" >> $LOG_FILE
EOF
```

crontab 수정:
```
* * * * * /home/user1/projects/cron_lab/monitor.sh
```

---

## 완료 확인 체크리스트

- [ ] `~/projects/cron_lab` 폴더가 있다
- [ ] `monitor.sh` 권한이 `-rwxr-xr-x`이다
- [ ] `crontab -l`에 등록된 작업이 보인다
- [ ] `logs/system.log`에 로그가 쌓인다
- [ ] 실습 후 crontab에서 삭제했다
- [ ] Git으로 커밋하고 Push했다
