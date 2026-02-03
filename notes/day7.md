# Day 7: 시스템 관리 기초

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 주제 | 부팅 프로세스, systemd, 패키지 관리 |
| 실습과제 | nginx 설치 및 서비스 관리 |

## 학습 목표

- 리눅스 부팅 프로세스 이해
- systemd를 활용한 서비스 관리 능력 습득
- apt/dpkg를 사용한 패키지 관리 숙달

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1h | 리눅스 부팅 프로세스 |
| 1.5h | systemd 서비스 관리 |
| 1.5h | 패키지 관리 (apt/dpkg) |
| 1h | 실습과제: nginx 설치 및 서비스 관리 |

---

## 1. 리눅스 부팅 프로세스 (1시간)

### 부팅 단계 개요

```
전원 ON → BIOS/UEFI → GRUB → 커널 → systemd → 로그인 화면
```

| 단계 | 역할 |
|------|------|
| BIOS/UEFI | 하드웨어 초기화, 부트로더 찾기 |
| GRUB | 커널 선택 및 로드 |
| 커널 | 하드웨어 드라이버 로드, init 프로세스 시작 |
| systemd | 서비스 시작, 시스템 초기화 완료 |

### GRUB 부트로더

```bash
# GRUB 설정 파일 위치
/boot/grub/grub.cfg

# GRUB 설정 확인 (읽기만)
cat /etc/default/grub
```

### 부팅 로그 확인

```bash
# 부팅 메시지 확인
dmesg | head -50

# systemd 부팅 로그
journalctl -b

# 부팅 시간 분석
systemd-analyze

# 서비스별 부팅 시간
systemd-analyze blame | head -10
```

---

## 2. systemd 서비스 관리 (1.5시간)

### systemd란?

- 리눅스 시스템 및 서비스 관리자
- PID 1로 실행되는 init 시스템
- 병렬 서비스 시작으로 빠른 부팅

### systemctl 기본 명령어

```bash
# 서비스 상태 확인
sudo systemctl status 서비스명

# 서비스 시작
sudo systemctl start 서비스명

# 서비스 중지
sudo systemctl stop 서비스명

# 서비스 재시작
sudo systemctl restart 서비스명

# 서비스 새로고침 (설정 다시 읽기)
sudo systemctl reload 서비스명
```

### 부팅 시 자동 시작 설정

```bash
# 부팅 시 자동 시작 활성화
sudo systemctl enable 서비스명

# 부팅 시 자동 시작 비활성화
sudo systemctl disable 서비스명

# 활성화 상태 확인
systemctl is-enabled 서비스명
```

### 서비스 목록 확인

```bash
# 모든 서비스 상태
systemctl list-units --type=service

# 실행 중인 서비스만
systemctl list-units --type=service --state=running

# 실패한 서비스
systemctl --failed
```

### 주요 서비스 예시

| 서비스 | 설명 |
|--------|------|
| ssh | SSH 원격 접속 서버 |
| apache2 | Apache 웹 서버 |
| mysql | MySQL 데이터베이스 |
| nginx | Nginx 웹 서버 |
| cron | 예약 작업 스케줄러 |
| ufw | 방화벽 |

### 실습: SSH 서비스 관리

```bash
# SSH 상태 확인
sudo systemctl status ssh

# SSH 재시작
sudo systemctl restart ssh

# SSH 자동 시작 확인
systemctl is-enabled ssh
```

---

## 3. 패키지 관리 (1.5시간)

### apt vs dpkg

| 도구 | 역할 |
|------|------|
| apt | 온라인 저장소에서 패키지 설치/관리 (의존성 자동 해결) |
| dpkg | 로컬 .deb 파일 직접 설치/관리 |

### apt 기본 명령어

```bash
# 패키지 목록 업데이트 (설치 전 필수!)
sudo apt update

# 패키지 검색
apt search 패키지명

# 패키지 정보 확인
apt show 패키지명

# 패키지 설치
sudo apt install 패키지명

# 패키지 삭제
sudo apt remove 패키지명

# 패키지 완전 삭제 (설정 파일 포함)
sudo apt purge 패키지명

# 설치된 패키지 업그레이드
sudo apt upgrade

# 불필요한 패키지 제거
sudo apt autoremove
```

### apt 실습

```bash
# 1. 패키지 목록 업데이트
sudo apt update

# 2. tree 패키지 검색
apt search tree

# 3. tree 정보 확인
apt show tree

# 4. tree 설치
sudo apt install tree -y

# 5. 설치 확인
tree --version

# 6. tree 사용해보기
tree /home -L 2
```

### dpkg 기본 명령어

```bash
# 설치된 패키지 목록
dpkg -l

# 특정 패키지 검색
dpkg -l | grep 패키지명

# 패키지가 설치한 파일 목록
dpkg -L 패키지명

# 파일이 어떤 패키지에 속하는지
dpkg -S /usr/bin/ls

# .deb 파일 직접 설치
sudo dpkg -i 파일명.deb

# 패키지 제거
sudo dpkg -r 패키지명
```

### dpkg 실습

```bash
# 설치된 패키지 개수 확인
dpkg -l | wc -l

# nginx 패키지 확인
dpkg -l | grep nginx

# ls 명령어가 어떤 패키지인지
dpkg -S /bin/ls
```

### 패키지 관리 흐름

```
1. sudo apt update          # 목록 업데이트
2. apt search 패키지명       # 검색
3. apt show 패키지명         # 정보 확인
4. sudo apt install 패키지명 # 설치
5. dpkg -l | grep 패키지명   # 설치 확인
```

---

## 4. 실습과제 7: nginx 설치 및 서비스 관리 (1시간)

### 과제 목표

- apt로 nginx 패키지 설치
- systemctl로 nginx 서비스 관리
- 웹 브라우저에서 nginx 동작 확인

### 실습 환경

```
/home/user1/projects/nginx_lab/
```

---

### Part 1: 실습 준비 (5분)

```bash
# 실습 디렉토리 생성
mkdir -p ~/projects/nginx_lab
cd ~/projects/nginx_lab
```

---

### Part 2: nginx 설치 (10분)

```bash
# 1. 패키지 목록 업데이트
sudo apt update

# 2. nginx 검색
apt search nginx | head -10

# 3. nginx 정보 확인
apt show nginx

# 4. nginx 설치
sudo apt install nginx -y

# 5. 설치 확인
dpkg -l | grep nginx
nginx -v
```

**예상 결과:**
```
nginx version: nginx/1.x.x (Ubuntu)
```

---

### Part 3: nginx 서비스 관리 (15분)

```bash
# 1. 서비스 상태 확인
sudo systemctl status nginx

# 2. 서비스 중지
sudo systemctl stop nginx

# 3. 상태 확인 (inactive)
sudo systemctl status nginx

# 4. 서비스 시작
sudo systemctl start nginx

# 5. 상태 확인 (active)
sudo systemctl status nginx

# 6. 자동 시작 상태 확인
systemctl is-enabled nginx

# 7. 자동 시작 비활성화
sudo systemctl disable nginx

# 8. 자동 시작 활성화
sudo systemctl enable nginx
```

---

### Part 4: 웹 페이지 확인 (10분)

```bash
# 1. 로컬에서 접속 테스트
curl http://localhost

# 2. IP 주소 확인
ip addr | grep "inet "

# 3. 웹 브라우저에서 확인
# 브라우저를 열고 http://localhost 또는 http://VM의IP주소 접속
```

**예상 결과:** "Welcome to nginx!" 페이지가 표시됨

---

### Part 5: 결과 저장 (10분)

```bash
# 결과 파일 생성
cd ~/projects/nginx_lab

# nginx 버전 저장
nginx -v 2>&1 > result.txt

# 서비스 상태 저장
echo "=== nginx 서비스 상태 ===" >> result.txt
sudo systemctl status nginx >> result.txt

# 자동 시작 상태 저장
echo "=== 자동 시작 상태 ===" >> result.txt
systemctl is-enabled nginx >> result.txt

# 결과 확인
cat result.txt
```

---

### Part 6: Git 제출 (10분)

```bash
cd ~/projects/nginx_lab

# Git 초기화 (처음인 경우)
git init

# 파일 추가
git add result.txt

# 커밋
git commit -m "Day 7 실습: nginx 설치 및 서비스 관리"

# 원격 저장소 연결 및 푸시 (GitHub 저장소 URL로 변경)
# git remote add origin https://github.com/사용자명/저장소명.git
# git push -u origin main
```

---

### 완료 체크리스트

- [ ] nginx 패키지 설치 완료
- [ ] nginx -v 명령어로 버전 확인
- [ ] systemctl status nginx로 상태 확인
- [ ] systemctl start/stop으로 시작/중지
- [ ] systemctl enable/disable로 자동 시작 설정
- [ ] curl http://localhost로 웹 페이지 확인
- [ ] result.txt 파일 생성 및 저장
- [ ] Git으로 제출

---

### 자주 하는 실수

| 실수 | 해결 방법 |
|------|-----------|
| apt install 전에 apt update 안 함 | `sudo apt update` 먼저 실행 |
| sudo 없이 systemctl 실행 | `sudo systemctl start nginx` |
| 서비스명 오타 | `systemctl list-units --type=service`로 확인 |
| 포트 80 접속 안 됨 | 방화벽 확인: `sudo ufw allow 80` |

---

### 평가 기준

| 항목 | 배점 |
|------|------|
| apt로 nginx 설치 | 25% |
| systemctl start/stop 사용 | 25% |
| systemctl enable/disable 사용 | 25% |
| 웹 페이지 접속 확인 | 15% |
| 결과 저장 및 Git 제출 | 10% |

---

## 핵심 명령어 정리

### 부팅 관련

```bash
dmesg               # 부팅 메시지
journalctl -b       # systemd 부팅 로그
systemd-analyze     # 부팅 시간 분석
```

### systemctl

```bash
systemctl status 서비스명    # 상태 확인
systemctl start 서비스명     # 시작
systemctl stop 서비스명      # 중지
systemctl restart 서비스명   # 재시작
systemctl enable 서비스명    # 자동 시작 활성화
systemctl disable 서비스명   # 자동 시작 비활성화
```

### apt

```bash
apt update           # 목록 업데이트
apt search 패키지명   # 검색
apt show 패키지명     # 정보
apt install 패키지명  # 설치
apt remove 패키지명   # 삭제
```

### dpkg

```bash
dpkg -l              # 설치된 패키지 목록
dpkg -L 패키지명      # 패키지가 설치한 파일
dpkg -S 파일경로      # 파일의 소속 패키지
```

---

## 예상 질문 및 답변

### Q: systemctl과 service 명령어 차이는?
**A**: service는 구버전 명령어, systemctl은 systemd용 최신 명령어. Ubuntu 16.04 이후로는 systemctl 사용 권장.

### Q: apt와 apt-get 차이는?
**A**: apt는 apt-get + apt-cache의 통합 명령어. 일반 사용자용으로 더 간편함. 스크립트에서는 apt-get이 더 안정적.

### Q: enable과 start 차이는?
**A**: start는 지금 당장 시작, enable은 부팅 시 자동 시작 설정. 둘 다 해야 "지금도 실행 + 부팅 시에도 실행".

---

## 다음 수업 예고

**Day 8**: 사용자 관리 및 네트워크
- 사용자/그룹 관리 (useradd, passwd)
- 네트워크 설정 (ip, ifconfig)
- SSH 원격 접속 (PuTTY, ssh-keygen)
