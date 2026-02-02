# Day 2~3 통합 실습: 리눅스 파일 탐정

## 실습 개요

| 항목 | 내용 |
|------|------|
| 환경 | VMware 우분투 |
| 소요 시간 | 1시간 30분 |
| 난이도 | 초급~중급 |

## 학습 목표

- 리눅스 파일 시스템 구조 이해 및 탐색
- 기본 파일/디렉토리 조작 명령어 숙달
- 셸 환경 설정 (alias, 환경변수)
- 리다이렉션과 파이프를 활용한 데이터 처리

---

## 시나리오

> 당신은 신입 시스템 관리자입니다. 팀장님이 서버 상태를 파악하고 정리하는 업무를 지시했습니다.
> 주어진 미션을 순서대로 완료하세요!

---

## Part 1: 작업 환경 구성 (15분)

### 미션 1-1: 프로젝트 폴더 구조 생성

홈 디렉토리에 다음 구조를 생성하세요:

```
~/mission/
├── logs/
├── reports/
├── scripts/
└── backup/
```

**힌트**: `mkdir -p`를 사용하면 중첩 디렉토리를 한 번에 생성할 수 있습니다.

<details>
<summary>정답 보기</summary>

```bash
cd ~
mkdir -p mission/{logs,reports,scripts,backup}
```

또는

```bash
mkdir -p ~/mission/logs ~/mission/reports ~/mission/scripts ~/mission/backup
```

</details>

### 미션 1-2: 결과 확인

생성한 폴더 구조를 확인하세요.

```bash
ls -la ~/mission
```

**체크포인트**: 4개의 디렉토리가 보이면 성공!

---

## Part 2: 시스템 탐색 (20분)

### 미션 2-1: 설정 파일 찾기

`/etc` 디렉토리에서 `.conf`로 끝나는 파일 10개를 찾아 `~/mission/reports/conf_list.txt`에 저장하세요.

**사용할 명령어**: `find`, `head`, `>`

<details>
<summary>힌트</summary>

```bash
find /etc -name "*.conf" 2>/dev/null | head -10
```

`2>/dev/null`은 권한 오류 메시지를 숨깁니다.

</details>

<details>
<summary>정답 보기</summary>

```bash
find /etc -name "*.conf" 2>/dev/null | head -10 > ~/mission/reports/conf_list.txt
```

</details>

### 미션 2-2: 로그 파일 분석

`/var/log` 디렉토리에서 파일 크기가 큰 순서로 상위 5개 파일을 찾아 `~/mission/reports/large_logs.txt`에 저장하세요.

**사용할 명령어**: `ls -lS`, `head`, `>`

<details>
<summary>정답 보기</summary>

```bash
ls -lS /var/log 2>/dev/null | head -6 > ~/mission/reports/large_logs.txt
```

</details>

### 미션 2-3: 사용자 정보 추출

`/etc/passwd` 파일에서 사용자 이름만 추출하여 `~/mission/reports/users.txt`에 저장하세요.

**사용할 명령어**: `cat`, `cut`, `|`, `>`

**힌트**: `/etc/passwd`는 `:`로 구분되어 있고, 첫 번째 필드가 사용자 이름입니다.

<details>
<summary>정답 보기</summary>

```bash
cat /etc/passwd | cut -d: -f1 > ~/mission/reports/users.txt
```

</details>

---

## Part 3: 셸 환경 설정 (20분)

### 미션 3-1: 유용한 alias 설정

다음 alias를 설정하고 `~/.bashrc`에 영구 저장하세요:

| alias | 명령어 | 설명 |
|-------|--------|------|
| `ll` | `ls -la` | 상세 목록 보기 |
| `cls` | `clear` | 화면 지우기 |
| `..` | `cd ..` | 상위 디렉토리 이동 |

**수행 단계**:

```bash
# 1. ~/.bashrc 파일 끝에 추가
echo "" >> ~/.bashrc
echo "# Custom aliases for mission" >> ~/.bashrc
echo "alias ll='ls -la'" >> ~/.bashrc
echo "alias cls='clear'" >> ~/.bashrc
echo "alias ..='cd ..'" >> ~/.bashrc

# 2. 변경사항 적용
source ~/.bashrc

# 3. 확인
alias
```

### 미션 3-2: alias 테스트

설정한 alias가 동작하는지 확인하세요:

```bash
ll          # ls -la와 동일한 결과가 나와야 함
cls         # 화면이 지워져야 함
cd ~/mission
..          # 상위 디렉토리로 이동해야 함
pwd         # /home/사용자명 이 출력되어야 함
```

---

## Part 4: 시스템 보고서 생성 (25분)

### 미션 4-1: 수동으로 보고서 만들기

리다이렉션을 사용하여 시스템 정보 보고서를 생성하세요.

```bash
cd ~/mission/reports

# 보고서 시작
echo "========================================" > system_report.txt
echo "       시스템 상태 보고서" >> system_report.txt
echo "========================================" >> system_report.txt
echo "" >> system_report.txt

# 날짜/시간
echo "[작성 시간]" >> system_report.txt
date >> system_report.txt
echo "" >> system_report.txt

# 사용자 정보
echo "[현재 사용자]" >> system_report.txt
whoami >> system_report.txt
echo "" >> system_report.txt

# 디스크 사용량
echo "[디스크 사용량]" >> system_report.txt
df -h >> system_report.txt
echo "" >> system_report.txt

# 메모리 사용량
echo "[메모리 사용량]" >> system_report.txt
free -h >> system_report.txt
echo "" >> system_report.txt

# 실행 중인 프로세스 수
echo "[프로세스 수]" >> system_report.txt
ps aux | wc -l >> system_report.txt
echo "" >> system_report.txt

echo "========================================" >> system_report.txt
echo "         보고서 끝" >> system_report.txt
echo "========================================" >> system_report.txt
```

### 미션 4-2: 보고서 확인

```bash
cat ~/mission/reports/system_report.txt
```

### 미션 4-3: 셸 스크립트로 자동화

위 작업을 스크립트로 만들어 보세요.

```bash
cd ~/mission/scripts

# 스크립트 파일 생성
cat << 'EOF' > report.sh
#!/bin/bash
# 시스템 상태 보고서 자동 생성 스크립트

REPORT_FILE=~/mission/reports/auto_report_$(date +%Y%m%d_%H%M%S).txt

echo "========================================" > $REPORT_FILE
echo "       시스템 상태 보고서" >> $REPORT_FILE
echo "       자동 생성: $(date)" >> $REPORT_FILE
echo "========================================" >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo "[시스템 정보]" >> $REPORT_FILE
echo "호스트명: $(hostname)" >> $REPORT_FILE
echo "커널: $(uname -r)" >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo "[디스크 사용량]" >> $REPORT_FILE
df -h | head -5 >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo "[메모리 사용량]" >> $REPORT_FILE
free -h >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo "[CPU 상위 5개 프로세스]" >> $REPORT_FILE
ps aux --sort=-%cpu | head -6 >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo "========================================" >> $REPORT_FILE
echo "보고서가 생성되었습니다: $REPORT_FILE"
EOF

# 실행 권한 부여
chmod +x report.sh

# 스크립트 실행
./report.sh
```

### 미션 4-4: 생성된 보고서 확인

```bash
ls ~/mission/reports/
cat ~/mission/reports/auto_report_*.txt
```

---

## Part 5: 파이프 활용 (10분)

### 미션 5-1: bash 쉘 사용자 찾기

`/etc/passwd`에서 `/bin/bash`를 사용하는 사용자만 찾아 `~/mission/reports/bash_users.txt`에 저장하세요.

<details>
<summary>정답 보기</summary>

```bash
cat /etc/passwd | grep "/bin/bash" | cut -d: -f1 > ~/mission/reports/bash_users.txt
```

</details>

### 미션 5-2: 명령어 히스토리 분석

가장 많이 사용한 명령어 상위 5개를 찾아보세요.

```bash
history | awk '{print $2}' | sort | uniq -c | sort -nr | head -5
```

---

## 제출 체크리스트

실습 완료 후 다음을 확인하세요:

```bash
# 1. 폴더 구조 확인
ls -laR ~/mission

# 2. 생성된 파일 목록
ls ~/mission/reports/

# 3. alias 설정 확인
alias | grep -E "ll|cls|\.\."

# 4. 스크립트 실행 가능 확인
ls -l ~/mission/scripts/report.sh
```

### 제출 내용

1. `ls -laR ~/mission` 실행 결과 스크린샷
2. `cat ~/mission/reports/system_report.txt` 결과 스크린샷
3. `alias` 명령어 결과 스크린샷
4. 스크립트 실행 결과 스크린샷

---

## 평가 기준

| 항목 | 배점 | 확인 방법 |
|------|------|-----------|
| 폴더 구조 생성 | 15% | `ls -laR ~/mission` |
| 시스템 탐색 (Part 2) | 25% | reports 폴더 내 파일 확인 |
| alias 설정 | 15% | `alias` 명령어 |
| 수동 보고서 생성 | 20% | system_report.txt 내용 |
| 스크립트 작성 | 25% | report.sh 실행 및 결과 |

---

## 보너스 미션 (선택)

### 보너스 1: 백업 스크립트

`~/mission/reports` 폴더를 `~/mission/backup`에 날짜별로 백업하는 스크립트를 만드세요.

<details>
<summary>힌트</summary>

```bash
cp -r ~/mission/reports ~/mission/backup/reports_$(date +%Y%m%d)
```

</details>

### 보너스 2: 로그 모니터링

터미널 하나를 열어 시스템 로그를 실시간 모니터링하세요.

```bash
tail -f /var/log/syslog
```

다른 터미널에서 작업하면서 로그가 어떻게 변하는지 관찰하세요.

---

## 트러블슈팅

### Q: "Permission denied" 오류가 발생해요
**A**: `2>/dev/null`을 추가하거나, 권한이 있는 디렉토리에서만 작업하세요.

### Q: alias가 적용되지 않아요
**A**: `source ~/.bashrc`를 실행했는지 확인하세요.

### Q: 스크립트가 실행되지 않아요
**A**: `chmod +x 스크립트명.sh`로 실행 권한을 부여했는지 확인하세요.

---

## 다음 단계

이 실습을 완료했다면 Day 4의 **권한 관리**와 **프로세스 관리**로 넘어갈 준비가 된 것입니다!
