# Day 3: 셸 기초

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 실습과제 | 셸 스크립트 작성 |

## 학습 목표

- 셸 환경 설정 및 명령어 조합 활용
- 리다이렉션과 파이프를 활용한 데이터 처리 이해

---

## 시간표

| 시간 | 내용 |
|------|------|
| 2h | Bash 셸: 환경변수, alias, 히스토리 |
| 2h | 리다이렉션, 파이프, 명령어 조합 |
| 1h | 실습과제 3 |

---

## 1. Bash 셸 기초 (2시간)

### 셸이란?

- 사용자와 커널 사이의 인터페이스
- 명령어를 해석하고 실행하는 프로그램
- Ubuntu 기본 셸: **Bash** (Bourne Again Shell)

```bash
# 현재 사용 중인 셸 확인
echo $SHELL

# 사용 가능한 셸 목록
cat /etc/shells
```

### 환경변수

#### 환경변수란?

- 셸과 프로그램이 사용하는 설정 값
- 대문자로 작성하는 것이 관례

#### 주요 환경변수

| 변수 | 설명 | 예시 |
|------|------|------|
| `HOME` | 사용자 홈 디렉토리 | `/home/ubuntu` |
| `USER` | 현재 사용자 이름 | `ubuntu` |
| `PATH` | 명령어 검색 경로 | `/usr/bin:/bin` |
| `PWD` | 현재 작업 디렉토리 | `/home/ubuntu` |
| `SHELL` | 현재 셸 경로 | `/bin/bash` |
| `LANG` | 시스템 언어 설정 | `en_US.UTF-8` |

#### 환경변수 확인 및 설정

```bash
# 환경변수 값 확인
echo $HOME
echo $PATH

# 모든 환경변수 보기
env
printenv

# 특정 변수 검색
env | grep PATH

# 환경변수 설정 (현재 세션만)
export MY_VAR="Hello"
echo $MY_VAR

# 변수 삭제
unset MY_VAR
```

#### PATH 환경변수

```bash
# PATH 확인
echo $PATH

# PATH에 경로 추가 (현재 세션)
export PATH=$PATH:/home/ubuntu/scripts

# 명령어 위치 확인
which ls
which python3
```

#### 영구적 환경변수 설정

```bash
# ~/.bashrc 파일에 추가
echo 'export MY_VAR="Hello"' >> ~/.bashrc

# 변경사항 즉시 적용
source ~/.bashrc
# 또는
. ~/.bashrc
```

### alias (명령어 별칭)

#### alias란?

- 긴 명령어를 짧은 이름으로 대체
- 자주 사용하는 명령어 조합 저장

```bash
# 현재 설정된 alias 보기
alias

# alias 설정 (현재 세션)
alias ll='ls -la'
alias ..='cd ..'
alias ...='cd ../..'
alias cls='clear'

# alias 사용
ll
..

# alias 삭제
unalias ll
```

#### 유용한 alias 예시

```bash
# 안전한 명령어
alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'

# 자주 사용하는 명령
alias update='sudo apt update && sudo apt upgrade -y'
alias ports='netstat -tuln'
alias myip='hostname -I'
```

#### 영구적 alias 설정

```bash
# ~/.bashrc 파일에 추가
echo "alias ll='ls -la'" >> ~/.bashrc
source ~/.bashrc
```

### 명령어 히스토리

```bash
# 히스토리 보기
history

# 최근 10개 명령어
history 10

# 히스토리에서 검색
history | grep "apt"

# 히스토리 명령 실행
!100      # 100번 명령어 실행
!!        # 직전 명령어 실행
!apt      # apt로 시작하는 최근 명령어 실행

# 히스토리 삭제
history -c
```

#### 히스토리 단축키

| 단축키 | 동작 |
|--------|------|
| `↑` / `↓` | 이전/다음 명령어 |
| `Ctrl + R` | 히스토리 역방향 검색 |
| `Ctrl + G` | 검색 취소 |

#### 히스토리 설정

```bash
# ~/.bashrc에서 설정
HISTSIZE=1000        # 메모리에 저장할 명령어 수
HISTFILESIZE=2000    # 파일에 저장할 명령어 수
HISTCONTROL=ignoredups    # 중복 명령어 무시
```

### Bash 설정 파일

| 파일 | 설명 | 실행 시점 |
|------|------|-----------|
| `/etc/profile` | 전체 사용자 설정 | 로그인 시 |
| `~/.bash_profile` | 사용자별 로그인 설정 | 로그인 시 |
| `~/.bashrc` | 사용자별 셸 설정 | 셸 시작 시 |
| `~/.bash_logout` | 로그아웃 시 실행 | 로그아웃 시 |

---

## 2. 리다이렉션과 파이프 (2시간)

### 표준 입출력

| 스트림 | 번호 | 설명 | 기본 장치 |
|--------|------|------|-----------|
| stdin | 0 | 표준 입력 | 키보드 |
| stdout | 1 | 표준 출력 | 화면 |
| stderr | 2 | 표준 에러 | 화면 |

### 출력 리다이렉션

```bash
# 표준 출력을 파일로 (덮어쓰기)
ls -la > filelist.txt

# 표준 출력을 파일로 (추가)
echo "New line" >> filelist.txt

# 표준 에러를 파일로
ls /nonexistent 2> error.txt

# 표준 출력과 에러 모두 파일로
ls /home /nonexistent > output.txt 2>&1
# 또는 (Bash 4+)
ls /home /nonexistent &> all.txt

# 출력 버리기 (휴지통)
ls /nonexistent 2> /dev/null
```

### 입력 리다이렉션

```bash
# 파일을 입력으로
wc -l < /etc/passwd

# Here Document (여러 줄 입력)
cat << EOF
첫 번째 줄
두 번째 줄
세 번째 줄
EOF

# Here Document로 파일 생성
cat << EOF > myfile.txt
Hello
World
EOF
```

### 파이프 (|)

- 앞 명령어의 출력을 뒤 명령어의 입력으로 전달

```bash
# 기본 사용
ls -la | less

# 파일 개수 세기
ls | wc -l

# 프로세스 검색
ps aux | grep apache

# 정렬하여 상위 5개
ls -lS | head -5

# 여러 파이프 연결
cat /etc/passwd | grep "/bin/bash" | wc -l
```

### 자주 사용하는 파이프 조합

```bash
# 로그에서 에러 찾기
cat /var/log/syslog | grep -i error

# 디스크 사용량 정렬
du -h /home | sort -h | tail -10

# 프로세스 메모리 사용량 상위 5개
ps aux | sort -k4 -nr | head -5

# 특정 포트 사용 확인
netstat -tuln | grep :80

# 파일 내용 중복 제거
cat file.txt | sort | uniq

# 단어 빈도수 세기
cat file.txt | tr ' ' '\n' | sort | uniq -c | sort -nr
```

### 유용한 필터 명령어

#### grep - 패턴 검색

```bash
grep "error" logfile.txt          # 기본 검색
grep -i "error" logfile.txt       # 대소문자 무시
grep -n "error" logfile.txt       # 줄 번호 표시
grep -v "error" logfile.txt       # 패턴 제외
grep -r "function" /home/ubuntu/  # 재귀 검색
grep -c "error" logfile.txt       # 매칭 횟수
```

#### sort - 정렬

```bash
sort file.txt           # 오름차순 정렬
sort -r file.txt        # 내림차순 정렬
sort -n file.txt        # 숫자로 정렬
sort -k2 file.txt       # 2번째 필드로 정렬
sort -u file.txt        # 중복 제거하며 정렬
```

#### uniq - 중복 제거

```bash
uniq file.txt           # 연속 중복 제거
sort file.txt | uniq    # 모든 중복 제거
uniq -c file.txt        # 중복 횟수 표시
uniq -d file.txt        # 중복된 것만 표시
```

#### wc - 단어/줄/문자 수 세기

```bash
wc file.txt             # 줄, 단어, 문자 수
wc -l file.txt          # 줄 수만
wc -w file.txt          # 단어 수만
wc -c file.txt          # 바이트 수만
```

#### cut - 필드 추출

```bash
cut -d: -f1 /etc/passwd         # : 구분자로 1번 필드
cut -d: -f1,3 /etc/passwd       # 1, 3번 필드
cut -c1-10 file.txt             # 1-10번째 문자
```

#### tr - 문자 변환/삭제

```bash
echo "hello" | tr 'a-z' 'A-Z'   # 대문자로 변환
echo "hello  world" | tr -s ' ' # 연속 공백 하나로
cat file.txt | tr -d '\r'       # Windows 줄바꿈 제거
```

### 명령어 조합 실습

```bash
# /etc/passwd에서 bash 사용자 목록
cat /etc/passwd | grep "/bin/bash" | cut -d: -f1

# 현재 디렉토리 파일 크기 합계
ls -l | awk '{sum += $5} END {print sum}'

# 로그 파일에서 IP 주소 추출 (예시)
cat access.log | cut -d' ' -f1 | sort | uniq -c | sort -nr | head -10
```

---

## 3. 실습과제 3: 셸 스크립트 작성 (1시간)

### 과제 목표

- 환경변수와 alias 설정
- 리다이렉션과 파이프 활용
- 간단한 셸 스크립트 작성

### 수행 단계

#### Part 1: 환경변수 및 alias 설정

1. 환경변수 확인

```bash
# 주요 환경변수 확인
echo "HOME: $HOME"
echo "USER: $USER"
echo "PATH: $PATH"
echo "SHELL: $SHELL"
```

2. alias 설정

```bash
# ~/.bashrc에 alias 추가
echo "" >> ~/.bashrc
echo "# Custom aliases" >> ~/.bashrc
echo "alias ll='ls -la'" >> ~/.bashrc
echo "alias cls='clear'" >> ~/.bashrc
echo "alias ..='cd ..'" >> ~/.bashrc

# 적용
source ~/.bashrc

# 확인
alias
```

#### Part 2: 리다이렉션 실습

1. 시스템 정보 저장

```bash
# 시스템 정보 파일 생성
cd ~
echo "=== System Info ===" > sysinfo.txt
echo "Date: $(date)" >> sysinfo.txt
echo "User: $USER" >> sysinfo.txt
echo "Hostname: $(hostname)" >> sysinfo.txt
echo "" >> sysinfo.txt
echo "=== Disk Usage ===" >> sysinfo.txt
df -h >> sysinfo.txt
echo "" >> sysinfo.txt
echo "=== Memory ===" >> sysinfo.txt
free -h >> sysinfo.txt

# 결과 확인
cat sysinfo.txt
```

#### Part 3: 파이프 활용

```bash
# 사용자 목록 추출
cat /etc/passwd | cut -d: -f1 | sort > users.txt

# bash 사용자만 추출
cat /etc/passwd | grep "/bin/bash" | cut -d: -f1 > bash_users.txt

# 로그 파일 분석 (줄 수 세기)
wc -l /var/log/*.log 2>/dev/null | sort -n | tail -5
```

#### Part 4: 셸 스크립트 작성

1. 스크립트 파일 생성

```bash
# 스크립트 디렉토리 생성
mkdir -p ~/scripts
cd ~/scripts

# 스크립트 파일 생성
cat << 'EOF' > system_report.sh
#!/bin/bash
# 시스템 정보 리포트 스크립트

echo "================================"
echo "    System Report"
echo "    $(date)"
echo "================================"
echo ""

echo "[User Info]"
echo "User: $USER"
echo "Home: $HOME"
echo ""

echo "[System Info]"
echo "Hostname: $(hostname)"
echo "Kernel: $(uname -r)"
echo ""

echo "[Disk Usage]"
df -h | head -5
echo ""

echo "[Memory Usage]"
free -h
echo ""

echo "[Top 5 Processes by Memory]"
ps aux --sort=-%mem | head -6
echo ""

echo "================================"
echo "    Report Complete"
echo "================================"
EOF
```

2. 실행 권한 부여 및 실행

```bash
# 실행 권한 부여
chmod +x system_report.sh

# 스크립트 실행
./system_report.sh

# 결과를 파일로 저장
./system_report.sh > ~/report_$(date +%Y%m%d).txt
```

3. PATH에 스크립트 디렉토리 추가

```bash
# ~/.bashrc에 PATH 추가
echo 'export PATH=$PATH:~/scripts' >> ~/.bashrc
source ~/.bashrc

# 어디서든 실행 가능
cd ~
system_report.sh
```

### 제출 내용

- `alias` 명령어 실행 결과 캡처
- `cat ~/sysinfo.txt` 결과 캡처
- `system_report.sh` 스크립트 내용 및 실행 결과 캡처

### 평가 기준

| 항목 | 배점 |
|------|------|
| alias 설정 | 20% |
| 리다이렉션 활용 | 30% |
| 파이프 활용 | 20% |
| 셸 스크립트 작성 | 30% |

---

## AI 활용 연습

### 셸 명령어 조합 질문하기

```
리눅스에서 특정 디렉토리 내 모든 .log 파일에서
"error" 문자열이 포함된 줄만 찾으려면 어떤 명령어를
사용해야 하나요?
```

```
ps와 grep을 조합해서 특정 프로세스가 실행 중인지
확인하는 방법을 알려주세요.
```

```
리다이렉션으로 명령어 출력을 파일에 저장하면서
동시에 화면에도 보이게 하려면 어떻게 하나요?
```

---

## 수업 진행 팁

- [ ] 리다이렉션 기호(`>`, `>>`, `<`, `|`) 혼동 주의
- [ ] `>` 사용 시 기존 파일 덮어쓰기 경고
- [ ] 파이프와 리다이렉션 차이점 명확히 설명
- [ ] Tab 자동완성 적극 활용 권장

---

## 예상 질문 및 답변

### Q: `>>`와 `>`의 차이가 뭔가요?
**A**: `>`는 파일을 새로 생성하거나 덮어씀. `>>`는 기존 파일 끝에 추가(append). 기존 내용 보존하려면 `>>` 사용.

### Q: 파이프와 리다이렉션의 차이는?
**A**: 파이프(`|`)는 명령어 간 데이터 전달. 리다이렉션(`>`, `<`)은 명령어와 파일 간 데이터 전달.

### Q: alias 설정이 재부팅 후 사라져요
**A**: 현재 세션 alias는 임시. 영구 저장하려면 `~/.bashrc` 파일에 추가 후 `source ~/.bashrc` 실행.

### Q: 스크립트가 실행이 안 돼요
**A**: 실행 권한 확인 (`ls -l`). 없으면 `chmod +x script.sh`로 권한 부여. 또는 `bash script.sh`로 직접 실행.

---

## 다음 수업 예고

**Day 4: 권한 관리와 프로세스**
- 파일 권한: chmod, chown, chgrp, umask
- 프로세스 관리: ps, top, kill, jobs, bg, fg, nohup
