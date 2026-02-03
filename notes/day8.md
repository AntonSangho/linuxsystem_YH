# Day 8: 사용자 관리 및 네트워크

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 주제 | 사용자/그룹 관리, 네트워크 설정, SSH 원격 접속 |
| 실습과제 | SSH 원격 접속 실습 |

## 학습 목표

- 사용자/그룹 생성, 수정, 삭제 명령어 숙달
- 네트워크 기본 설정 및 상태 확인 능력 습득
- SSH를 통한 안전한 원격 접속 능력 습득

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1h | 사용자 계정 관리 |
| 0.5h | 그룹 관리 |
| 1h | 네트워크 설정 |
| 1h | 네트워크 상태 확인 |
| 1.5h | SSH 원격 접속 실습 |

---

## 1. 사용자 계정 관리 (1시간)

### 사용자 관련 파일

| 파일 | 내용 |
|------|------|
| /etc/passwd | 사용자 계정 정보 |
| /etc/shadow | 암호화된 비밀번호 |
| /etc/group | 그룹 정보 |

### /etc/passwd 구조

```bash
cat /etc/passwd | head -5
```

```
사용자명:x:UID:GID:설명:홈디렉토리:셸
user1:x:1000:1000:User One:/home/user1:/bin/bash
```

| 필드 | 설명 |
|------|------|
| 사용자명 | 로그인 이름 |
| x | 비밀번호 (/etc/shadow에 저장) |
| UID | 사용자 고유 번호 (0=root, 1000+ 일반 사용자) |
| GID | 기본 그룹 번호 |
| 설명 | 사용자 설명 (이름 등) |
| 홈디렉토리 | 로그인 시 시작 위치 |
| 셸 | 기본 셸 프로그램 |

### useradd - 사용자 생성

```bash
# 기본 사용자 생성
sudo useradd testuser

# 홈 디렉토리 자동 생성 (-m)
sudo useradd -m testuser

# 셸 지정
sudo useradd -m -s /bin/bash testuser

# 설명 추가
sudo useradd -m -s /bin/bash -c "Test User" testuser
```

### adduser - 대화형 사용자 생성

```bash
# 대화형으로 사용자 생성 (Ubuntu 권장)
sudo adduser testuser
# 비밀번호, 이름 등을 대화형으로 입력
```

### passwd - 비밀번호 설정

```bash
# 다른 사용자 비밀번호 설정 (root만 가능)
sudo passwd testuser

# 자신의 비밀번호 변경
passwd
```

### usermod - 사용자 정보 수정

```bash
# 셸 변경
sudo usermod -s /bin/zsh testuser

# 홈 디렉토리 변경
sudo usermod -d /home/newdir testuser

# 그룹 추가 (기존 그룹 유지하며 추가)
sudo usermod -aG sudo testuser

# 사용자명 변경
sudo usermod -l newname oldname
```

### userdel - 사용자 삭제

```bash
# 사용자만 삭제 (홈 디렉토리 유지)
sudo userdel testuser

# 홈 디렉토리까지 삭제
sudo userdel -r testuser
```

### 실습: 사용자 생성 및 관리

```bash
# 1. 사용자 생성
sudo adduser devuser

# 2. 사용자 확인
cat /etc/passwd | grep devuser

# 3. 사용자 전환
su - devuser

# 4. 원래 사용자로 돌아가기
exit

# 5. 사용자 삭제
sudo userdel -r devuser
```

---

## 2. 그룹 관리 (30분)

### 그룹 관련 명령어

```bash
# 현재 사용자의 그룹 확인
groups

# 특정 사용자의 그룹 확인
groups testuser

# 그룹 목록 확인
cat /etc/group
```

### groupadd - 그룹 생성

```bash
# 그룹 생성
sudo groupadd developers

# GID 지정
sudo groupadd -g 1500 developers
```

### 사용자를 그룹에 추가

```bash
# 사용자를 기존 그룹에 추가 (-a: append, -G: 보조그룹)
sudo usermod -aG developers testuser

# 확인
groups testuser
```

### groupdel - 그룹 삭제

```bash
sudo groupdel developers
```

### 실습: 그룹 관리

```bash
# 1. 그룹 생성
sudo groupadd webteam

# 2. 사용자를 그룹에 추가
sudo usermod -aG webteam $USER

# 3. 그룹 확인 (재로그인 후 적용)
groups

# 4. /etc/group에서 확인
cat /etc/group | grep webteam
```

---

## 3. 네트워크 설정 (1시간)

### IP 주소 개념

| 종류 | 범위 | 설명 |
|------|------|------|
| 사설 IP | 192.168.x.x, 10.x.x.x, 172.16-31.x.x | 내부 네트워크용 |
| 공인 IP | 그 외 | 인터넷 접속용 |
| localhost | 127.0.0.1 | 자기 자신 |

### ip 명령어 (최신)

```bash
# IP 주소 확인
ip addr
ip a

# 특정 인터페이스만
ip addr show eth0

# 간단히 보기
ip -br addr

# 라우팅 테이블
ip route
```

### ifconfig 명령어 (구버전)

```bash
# net-tools 패키지 필요
sudo apt install net-tools

# 네트워크 정보 확인
ifconfig

# 특정 인터페이스
ifconfig eth0
```

### 네트워크 인터페이스

| 인터페이스 | 설명 |
|------------|------|
| lo | 로컬 루프백 (127.0.0.1) |
| eth0, ens33 | 유선 이더넷 |
| wlan0 | 무선 Wi-Fi |

### 실습: IP 주소 확인

```bash
# 1. ip 명령어로 확인
ip addr

# 2. 간단히 보기
ip -br addr

# 3. ifconfig 설치 및 사용
sudo apt install net-tools -y
ifconfig

# 4. 내 IP만 추출
ip addr | grep "inet " | grep -v 127.0.0.1
```

---

## 4. 네트워크 상태 확인 (1시간)

### ping - 연결 테스트

```bash
# 기본 ping (Ctrl+C로 중지)
ping google.com

# 횟수 지정
ping -c 4 google.com

# 로컬 네트워크 테스트
ping 192.168.1.1
```

### netstat - 네트워크 상태 (구버전)

```bash
# 열린 포트 확인
netstat -tuln

# 연결된 세션 확인
netstat -an

# 프로세스 포함
sudo netstat -tulnp
```

### ss - 네트워크 상태 (최신)

```bash
# 열린 포트 확인
ss -tuln

# 연결 확인
ss -an

# 프로세스 포함
sudo ss -tulnp
```

### 옵션 설명

| 옵션 | 의미 |
|------|------|
| -t | TCP |
| -u | UDP |
| -l | LISTEN 상태만 |
| -n | 숫자로 표시 (포트번호) |
| -p | 프로세스 정보 |

### 실습: 네트워크 상태 확인

```bash
# 1. 외부 연결 테스트
ping -c 4 8.8.8.8

# 2. 도메인 연결 테스트
ping -c 4 google.com

# 3. 열린 포트 확인
ss -tuln

# 4. SSH 포트 확인
ss -tuln | grep :22
```

---

## 5. SSH 원격 접속 (1.5시간)

### SSH란?

- Secure Shell: 암호화된 원격 접속 프로토콜
- 포트: 22번
- 텔넷(Telnet)의 안전한 대안

### SSH 서버 설치

```bash
# SSH 서버 설치
sudo apt install openssh-server -y

# 서비스 시작 및 활성화
sudo systemctl start ssh
sudo systemctl enable ssh

# 상태 확인
sudo systemctl status ssh
```

### SSH 클라이언트 접속

```bash
# 기본 접속
ssh 사용자명@IP주소

# 예시
ssh user1@192.168.1.100

# 포트 지정 (기본 22가 아닐 때)
ssh -p 2222 user1@192.168.1.100
```

### Windows에서 PuTTY 사용

1. PuTTY 다운로드: https://www.putty.org/
2. Host Name: VM의 IP 주소 입력
3. Port: 22
4. Connection type: SSH 선택
5. Open 클릭
6. 사용자명과 비밀번호 입력

### scp - 파일 전송

```bash
# 로컬 → 원격
scp 파일명 사용자@IP:경로
scp test.txt user1@192.168.1.100:/home/user1/

# 원격 → 로컬
scp 사용자@IP:경로 로컬경로
scp user1@192.168.1.100:/home/user1/test.txt ./

# 디렉토리 전송 (-r)
scp -r 폴더명 사용자@IP:경로
```

### ssh-keygen - 키 인증 설정

```bash
# 1. 키 생성 (클라이언트에서)
ssh-keygen -t rsa -b 4096
# Enter 3번 (기본 경로, 비밀번호 없음)

# 2. 공개키 확인
cat ~/.ssh/id_rsa.pub

# 3. 공개키를 서버에 복사
ssh-copy-id user1@192.168.1.100

# 4. 이제 비밀번호 없이 접속 가능
ssh user1@192.168.1.100
```

### SSH 키 파일

| 파일 | 설명 |
|------|------|
| ~/.ssh/id_rsa | 개인키 (절대 공유 금지!) |
| ~/.ssh/id_rsa.pub | 공개키 (서버에 등록) |
| ~/.ssh/authorized_keys | 서버에 등록된 공개키 목록 |

---

## 6. 실습과제 8: SSH 원격 접속 (1.5시간)

### 과제 목표

- SSH 서버 설치 및 설정
- 비밀번호 방식으로 SSH 접속
- SSH 키 인증 설정

### 실습 환경

```
/home/user1/projects/ssh_lab/
```

---

### Part 1: 실습 준비 (5분)

```bash
# 실습 디렉토리 생성
mkdir -p ~/projects/ssh_lab
cd ~/projects/ssh_lab
```

---

### Part 2: SSH 서버 확인 (10분)

```bash
# 1. SSH 서버 상태 확인
sudo systemctl status ssh

# 2. SSH 포트 확인
ss -tuln | grep :22

# 3. VM의 IP 주소 확인
ip addr | grep "inet " | grep -v 127.0.0.1
```

**예상 결과:** SSH 서비스가 active 상태, 포트 22가 LISTEN 상태

---

### Part 3: SSH 접속 테스트 (15분)

```bash
# 1. 자기 자신에게 SSH 접속
ssh $USER@localhost

# 2. 접속 확인 후 종료
hostname
exit

# 3. IP 주소로 접속 (VM의 IP 사용)
ssh $USER@192.168.xxx.xxx
```

---

### Part 4: SSH 키 생성 (15분)

```bash
# 1. SSH 키 생성
ssh-keygen -t rsa -b 4096
# Enter 3번 입력 (기본값 사용)

# 2. 키 파일 확인
ls -la ~/.ssh/

# 3. 공개키 내용 확인
cat ~/.ssh/id_rsa.pub

# 4. 공개키를 authorized_keys에 등록 (자기 자신)
ssh-copy-id $USER@localhost
```

---

### Part 5: 키 인증 접속 테스트 (10분)

```bash
# 1. 비밀번호 없이 접속 확인
ssh $USER@localhost

# 2. 접속 성공 확인
whoami
hostname

# 3. 종료
exit
```

---

### Part 6: scp 파일 전송 (15분)

```bash
# 1. 테스트 파일 생성
cd ~/projects/ssh_lab
echo "SSH Test File" > test.txt

# 2. 파일 전송 (자기 자신에게)
scp test.txt $USER@localhost:/tmp/

# 3. 전송 확인
ls /tmp/test.txt

# 4. 원격에서 가져오기
scp $USER@localhost:/tmp/test.txt ./received.txt

# 5. 확인
cat received.txt
```

---

### Part 7: 결과 저장 (10분)

```bash
cd ~/projects/ssh_lab

# 결과 파일 생성
echo "=== SSH 실습 결과 ===" > result.txt

# IP 주소
echo "=== IP 주소 ===" >> result.txt
ip -br addr >> result.txt

# SSH 상태
echo "=== SSH 서비스 상태 ===" >> result.txt
systemctl is-active ssh >> result.txt

# SSH 키 목록
echo "=== SSH 키 파일 ===" >> result.txt
ls -la ~/.ssh/ >> result.txt

# 결과 확인
cat result.txt
```

---

### Part 8: Git 제출 (10분)

```bash
cd ~/projects/ssh_lab

# Git 초기화
git init

# 파일 추가 (키 파일 제외!)
git add result.txt test.txt received.txt

# 커밋
git commit -m "Day 8 실습: SSH 원격 접속"

# 원격 저장소 연결 및 푸시
# git remote add origin https://github.com/사용자명/저장소명.git
# git push -u origin main
```

**주의:** SSH 개인키(id_rsa)는 절대 Git에 올리지 않습니다!

---

### 완료 체크리스트

- [ ] SSH 서버 상태 확인 (systemctl status ssh)
- [ ] VM IP 주소 확인
- [ ] SSH 비밀번호 접속 성공
- [ ] SSH 키 생성 완료
- [ ] SSH 키 인증 접속 성공
- [ ] scp로 파일 전송 성공
- [ ] result.txt 저장
- [ ] Git 제출 (키 파일 제외)

---

### 자주 하는 실수

| 실수 | 해결 방법 |
|------|-----------|
| SSH 연결 거부 | `sudo systemctl start ssh` |
| 포트 22 차단 | `sudo ufw allow 22` |
| 권한 오류 | `chmod 700 ~/.ssh`, `chmod 600 ~/.ssh/id_rsa` |
| 키 인증 실패 | `ssh-copy-id` 재실행, 권한 확인 |
| 개인키 Git 업로드 | 즉시 삭제 후 새 키 생성 |

---

### 평가 기준

| 항목 | 배점 |
|------|------|
| SSH 서버 설정 | 20% |
| SSH 접속 성공 | 20% |
| SSH 키 생성 | 20% |
| 키 인증 접속 | 20% |
| scp 파일 전송 | 10% |
| Git 제출 | 10% |

---

## 핵심 명령어 정리

### 사용자 관리

```bash
sudo adduser 사용자명     # 사용자 생성 (대화형)
sudo passwd 사용자명      # 비밀번호 설정
sudo usermod -aG 그룹 사용자  # 그룹 추가
sudo userdel -r 사용자명  # 사용자 삭제
```

### 네트워크

```bash
ip addr                  # IP 주소 확인
ping -c 4 호스트         # 연결 테스트
ss -tuln                 # 열린 포트 확인
```

### SSH

```bash
ssh 사용자@IP            # SSH 접속
scp 파일 사용자@IP:경로   # 파일 전송
ssh-keygen -t rsa        # 키 생성
ssh-copy-id 사용자@IP    # 공개키 등록
```

---

## 예상 질문 및 답변

### Q: useradd와 adduser 차이는?
**A**: useradd는 저수준 명령어(옵션 직접 지정), adduser는 Ubuntu의 대화형 스크립트(자동으로 홈 디렉토리 생성, 비밀번호 설정). 초보자는 adduser 권장.

### Q: SSH 키 인증이 안전한 이유는?
**A**: 비밀번호는 추측/탈취 가능하지만, SSH 키는 2048/4096비트 암호화. 개인키 없이는 접속 불가능.

### Q: 포트 22가 아닌 다른 포트로 SSH를 변경하려면?
**A**: `/etc/ssh/sshd_config`에서 `Port 22`를 다른 번호로 변경 후 `sudo systemctl restart ssh`. 방화벽도 해당 포트 열어야 함.

---

## 다음 수업 예고

**Day 9**: 웹 서버, 데이터베이스, 보안
- Apache2 웹 서버 구축
- MySQL 데이터베이스 설치
- 방화벽(ufw) 설정
