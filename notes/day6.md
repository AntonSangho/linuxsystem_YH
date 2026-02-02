# Day 6: 프로세스 관리

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 교재 범위 | 프로세스 개념, 관리, crontab |
| 실습과제 | 프로세스 모니터링 및 cron 작업 설정 |

## 학습 목표

- 프로세스 개념과 PID 이해
- ps, top, kill 명령어 숙달
- crontab을 이용한 작업 스케줄링

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1h | 프로세스 개념 (프로세스 번호) |
| 1h | 프로세스 관리 명령: ps, pgrep |
| 1h | 프로세스 종료: kill |
| 1h | 프로세스 관리도구: top |
| 1h | 정해진 시간에 반복실행: crontab |

---

## 1. 프로세스 개념 (1시간)

### 프로세스란?

- 실행 중인 프로그램의 인스턴스
- 각 프로세스는 고유한 **PID (Process ID)** 를 가짐

### 프로세스 번호 (PID)

| 용어 | 설명 |
|------|------|
| PID | 프로세스 고유 번호 |
| PPID | 부모 프로세스 번호 |
| UID | 프로세스 실행 사용자 |

```bash
# 현재 셸의 PID 확인
echo $$

# 부모 프로세스 PID 확인
echo $PPID
```

### 프로세스 상태

| 상태 | 기호 | 설명 |
|------|------|------|
| Running | R | 실행 중 |
| Sleeping | S | 대기 중 |
| Stopped | T | 중지됨 |
| Zombie | Z | 종료되었으나 정리 안됨 |

---

## 2. 프로세스 관리 명령: ps, pgrep (1시간)

### ps - 프로세스 목록 확인

```bash
# 현재 터미널의 프로세스
ps

# 모든 프로세스 (자주 사용)
ps aux

# 모든 프로세스 (다른 형식)
ps -ef
```

### ps aux 출력 해석

```
USER       PID %CPU %MEM    VSZ   RSS TTY      STAT START   TIME COMMAND
root         1  0.0  0.1 168936 11420 ?        Ss   10:00   0:01 /sbin/init
ubuntu    1234  0.5  1.2 123456 12345 pts/0    S    10:30   0:05 python3
```

| 필드 | 설명 |
|------|------|
| USER | 실행 사용자 |
| PID | 프로세스 번호 |
| %CPU | CPU 사용률 |
| %MEM | 메모리 사용률 |
| STAT | 프로세스 상태 |
| COMMAND | 실행 명령어 |

### ps 활용 예제

```bash
# 특정 프로세스 검색
ps aux | grep apache
ps aux | grep python

# CPU 사용량 순 정렬
ps aux --sort=-%cpu | head

# 메모리 사용량 순 정렬
ps aux --sort=-%mem | head
```

### pgrep - 프로세스 검색

```bash
# 이름으로 PID 찾기
pgrep apache
pgrep python

# 프로세스 이름과 함께 출력
pgrep -l apache

# 특정 사용자의 프로세스
pgrep -u ubuntu
```

---

## 3. 프로세스 종료: kill (1시간)

### kill 기본 사용법

```bash
# 프로세스 종료 (기본: SIGTERM)
kill PID
kill 1234

# 강제 종료 (SIGKILL)
kill -9 PID
kill -9 1234
```

### 주요 시그널

| 시그널 | 번호 | 설명 |
|--------|------|------|
| SIGTERM | 15 | 정상 종료 (기본값) |
| SIGKILL | 9 | 강제 종료 |
| SIGHUP | 1 | 재시작 |
| SIGINT | 2 | 인터럽트 (Ctrl+C) |

### 프로세스 이름으로 종료

```bash
# pkill - 이름으로 종료
pkill python
pkill -9 apache

# killall - 모든 동일 이름 프로세스 종료
killall firefox
```

### 실무 예시

```bash
# Apache 재시작
sudo kill -1 $(pgrep apache2)

# 응답 없는 프로세스 강제 종료
kill -9 $(pgrep -f "stuck_script.py")
```

---

## 4. 프로세스 관리도구: top (1시간)

### top 실행

```bash
top
```

### top 화면 구성

```
top - 10:30:00 up 1 day,  2:30,  1 user,  load average: 0.00, 0.01, 0.05
Tasks: 120 total,   1 running, 119 sleeping,   0 stopped,   0 zombie
%Cpu(s):  0.3 us,  0.1 sy,  0.0 ni, 99.5 id,  0.0 wa
MiB Mem :   3936.5 total,   2500.0 free,    800.0 used
```

| 항목 | 설명 |
|------|------|
| load average | 시스템 부하 (1분, 5분, 15분) |
| Tasks | 프로세스 수 |
| %Cpu | CPU 사용률 |
| Mem | 메모리 사용량 |

### top 단축키

| 키 | 동작 |
|----|------|
| `q` | 종료 |
| `k` | 프로세스 종료 (PID 입력) |
| `M` | 메모리 순 정렬 |
| `P` | CPU 순 정렬 |
| `u` | 특정 사용자 필터 |
| `1` | CPU 코어별 표시 |

### top 옵션

```bash
# 1회만 출력 (스크립트용)
top -bn1 | head -20

# 특정 사용자만
top -u ubuntu
```

---

## 5. 정해진 시간에 반복실행: crontab (1시간)

### crontab이란?

- 정해진 시간에 명령어/스크립트를 자동 실행
- 서버 관리에 필수 (백업, 로그 정리, 모니터링)

### crontab 명령어

```bash
# crontab 편집
crontab -e

# crontab 목록 확인
crontab -l

# crontab 삭제
crontab -r
```

### cron 표현식

```
분  시  일  월  요일  명령어
*   *   *   *   *     command
│   │   │   │   │
│   │   │   │   └─ 요일 (0-7, 0과 7은 일요일)
│   │   │   └───── 월 (1-12)
│   │   └───────── 일 (1-31)
│   └───────────── 시 (0-23)
└─────────────────  분 (0-59)
```

### cron 표현식 예제

| 표현식 | 의미 |
|--------|------|
| `0 9 * * *` | 매일 오전 9시 |
| `30 18 * * *` | 매일 오후 6시 30분 |
| `0 0 * * *` | 매일 자정 |
| `0 */2 * * *` | 2시간마다 |
| `0 9 * * 1` | 매주 월요일 오전 9시 |
| `0 0 1 * *` | 매월 1일 자정 |

### crontab 실무 예제

```bash
# crontab 편집
crontab -e

# 아래 내용 추가:

# 매일 자정 백업
0 0 * * * /home/ubuntu/scripts/backup.sh

# 매시간 로그 정리
0 * * * * /home/ubuntu/scripts/cleanup.sh

# 5분마다 서비스 상태 확인
*/5 * * * * /home/ubuntu/scripts/health_check.sh

# 매주 일요일 새벽 3시 시스템 업데이트
0 3 * * 0 sudo apt update && sudo apt upgrade -y
```

### crontab 주의사항

```bash
# 1. 절대 경로 사용
0 9 * * * /home/ubuntu/scripts/backup.sh   # (O)
0 9 * * * ./backup.sh                       # (X)

# 2. 로그 남기기
0 9 * * * /home/ubuntu/scripts/backup.sh >> /home/ubuntu/logs/backup.log 2>&1

# 3. 환경변수 설정 필요시 스크립트 내에서 처리
```

---

## 6. 실습과제 6: 프로세스 관리 및 crontab (1시간)

### 과제 목표

- 프로세스 모니터링 명령어 숙달
- crontab 설정 실습

### 수행 단계

#### Part 1: 프로세스 확인

```bash
# 모든 프로세스 확인
ps aux | head -10

# CPU 상위 5개
ps aux --sort=-%cpu | head -6

# 메모리 상위 5개
ps aux --sort=-%mem | head -6
```

#### Part 2: pgrep, kill 실습

```bash
# 백그라운드 프로세스 시작
sleep 300 &
echo "PID: $!"

# pgrep으로 찾기
pgrep sleep

# kill로 종료
kill $(pgrep sleep)

# 확인
pgrep sleep
```

#### Part 3: top 사용

```bash
# top 실행 후:
# - P 키: CPU 순 정렬
# - M 키: 메모리 순 정렬
# - q 키: 종료
top
```

#### Part 4: crontab 설정

```bash
# 간단한 스크립트 생성
mkdir -p ~/scripts ~/logs
cat << 'EOF' > ~/scripts/hello_cron.sh
#!/bin/bash
echo "$(date): Hello from cron!" >> ~/logs/cron.log
EOF
chmod +x ~/scripts/hello_cron.sh

# crontab 등록 (1분마다 실행)
crontab -e
# 아래 내용 추가:
# */1 * * * * /home/ubuntu/scripts/hello_cron.sh

# 2-3분 후 로그 확인
cat ~/logs/cron.log

# 테스트 후 crontab 정리
crontab -r
```

### 제출 내용

- `ps aux --sort=-%cpu | head -6` 결과 캡처
- `crontab -l` 결과 캡처
- cron 로그 파일 내용 캡처

### 평가 기준

| 항목 | 배점 |
|------|------|
| ps, pgrep 사용 | 25% |
| kill 사용 | 25% |
| top 사용 | 20% |
| crontab 설정 | 30% |

---

## 예상 질문 및 답변

### Q: kill과 kill -9 차이는?
**A**: kill은 SIGTERM(15)으로 정상 종료 요청. 프로그램이 무시할 수 있음. kill -9는 SIGKILL로 강제 종료, 무시 불가.

### Q: crontab이 실행 안 됩니다
**A**: 1) 절대 경로 사용 확인 2) 스크립트 실행 권한 확인 (chmod +x) 3) cron 서비스 실행 확인 (systemctl status cron)

### Q: 좀비 프로세스란?
**A**: 종료되었지만 부모가 종료 상태를 수집 안 한 프로세스. 리소스 거의 안 씀. 부모 종료 시 자동 정리됨.

---

## 다음 수업 예고

**Day 7**: (교재 내용에 따라 결정)
