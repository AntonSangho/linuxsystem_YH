# Day 5: 파일 소유권과 권한 심화

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 교재 범위 | 파일 속성과 그룹, 권한 변경 심화 |
| 실습과제 | 소유자/그룹 변경 및 권한 설정 |

## 학습 목표

- 파일 소유자와 그룹 개념 이해
- chown, chgrp 명령어 숙달
- 권한 변경 실무 적용

---

## 시간표

| 시간 | 내용 |
|------|------|
| 2h | 파일 속성과 그룹 |
| 1.5h | 기호를 이용한 권한 변경 (심화) |
| 0.5h | 숫자를 이용한 권한 변경 (심화) |
| 1h | 실습과제 5 |

---

## 1. 파일 속성과 그룹 (2시간)

### 소유자와 그룹 확인

```bash
ls -l file.txt
# -rw-r--r-- 1 ubuntu ubuntu 1234 Jan 30 10:00 file.txt
#              ^^^^^^ ^^^^^^
#              소유자  그룹
```

### 그룹이란?

- 여러 사용자를 묶어서 권한을 부여하는 단위
- 한 사용자는 여러 그룹에 속할 수 있음
- 파일/디렉토리는 하나의 그룹에 속함

### 그룹 관련 명령어

```bash
# 현재 사용자가 속한 그룹 확인
groups

# 특정 사용자의 그룹 확인
groups ubuntu

# 시스템의 모든 그룹 확인
cat /etc/group
```

### chown - 소유자 변경

```bash
# 소유자 변경
sudo chown newuser file.txt

# 소유자와 그룹 동시 변경
sudo chown newuser:newgroup file.txt

# 그룹만 변경 (chown 사용)
sudo chown :newgroup file.txt

# 재귀적 변경 (하위 디렉토리 포함)
sudo chown -R newuser:newgroup mydir/
```

### chgrp - 그룹 변경

```bash
# 그룹 변경
sudo chgrp developers file.txt

# 재귀적 변경
sudo chgrp -R developers mydir/
```

### 실무 예시: 웹 서버 파일 권한

```bash
# 웹 서버 파일 소유권 설정 (Apache)
sudo chown -R www-data:www-data /var/www/html/

# 개발자가 수정 가능하도록 그룹 설정
sudo chown -R www-data:developers /var/www/html/
sudo chmod -R 775 /var/www/html/
```

---

## 2. 기호를 이용한 권한 변경 - 심화 (1.5시간)

### 복습: 기본 문법

```bash
chmod [대상][연산자][권한] 파일
```

| 대상 | 연산자 | 권한 |
|------|--------|------|
| u (소유자) | + (추가) | r (읽기) |
| g (그룹) | - (제거) | w (쓰기) |
| o (기타) | = (설정) | x (실행) |
| a (전체) | | |

### 복합 권한 변경

```bash
# 소유자에게 실행 추가, 그룹에서 쓰기 제거
chmod u+x,g-w script.sh

# 소유자/그룹에게 읽기쓰기, 기타는 읽기만
chmod ug=rw,o=r file.txt

# 모두에게 실행 권한 추가
chmod a+x script.sh

# 그룹과 기타에게서 모든 권한 제거
chmod go-rwx private.txt
```

### 디렉토리 권한의 의미

| 권한 | 디렉토리에서의 의미 |
|------|---------------------|
| r | ls로 목록 볼 수 있음 |
| w | 파일 생성/삭제 가능 |
| x | cd로 진입 가능 |

```bash
# 디렉토리에 진입만 가능 (목록 못 봄)
chmod 711 mydir/

# 디렉토리 목록 보기 가능, 진입 불가 (실용성 없음)
chmod 744 mydir/
```

---

## 3. 숫자를 이용한 권한 변경 - 심화 (30분)

### 실무에서 자주 쓰는 권한

| 숫자 | 의미 | 용도 |
|------|------|------|
| 755 | rwxr-xr-x | 실행 파일, 디렉토리 |
| 644 | rw-r--r-- | 일반 파일, 설정 파일 |
| 600 | rw------- | SSH 키, 비밀 파일 |
| 700 | rwx------ | 개인 스크립트, .ssh 디렉토리 |
| 775 | rwxrwxr-x | 그룹 공유 디렉토리 |
| 664 | rw-rw-r-- | 그룹 공유 파일 |

### AWS EC2 SSH 접속 시 필수 권한

```bash
# SSH 키 파일 권한 (필수!)
chmod 600 ~/.ssh/my-key.pem

# .ssh 디렉토리 권한
chmod 700 ~/.ssh

# authorized_keys 파일 권한
chmod 600 ~/.ssh/authorized_keys
```

---

## 4. 실습과제 5: 소유권과 권한 설정 (1시간)

### 과제 목표

- 소유자/그룹 변경 실습
- 실무 권한 설정 연습

### 수행 단계

#### Part 1: 그룹 확인

```bash
# 현재 사용자 그룹 확인
groups
id
```

#### Part 2: 소유자/그룹 변경 실습

```bash
cd ~
mkdir -p ownership_lab
cd ownership_lab
touch team_file.txt personal_file.txt

# 현재 소유자/그룹 확인
ls -l

# 그룹 변경 (자신이 속한 그룹으로)
chgrp $(groups | cut -d' ' -f1) team_file.txt
ls -l
```

#### Part 3: 팀 공유 디렉토리 시뮬레이션

```bash
mkdir shared_project

# 그룹 공유용 권한 설정
chmod 775 shared_project/

# 내부 파일도 그룹 공유
touch shared_project/readme.txt
chmod 664 shared_project/readme.txt

ls -la shared_project/
```

#### Part 4: SSH 키 권한 실습

```bash
mkdir -p ~/.ssh_test
touch ~/.ssh_test/fake_key.pem

# SSH 키 권한 설정
chmod 600 ~/.ssh_test/fake_key.pem
chmod 700 ~/.ssh_test/

ls -la ~/.ssh_test/

# 정리
rm -rf ~/.ssh_test/
```

### 제출 내용

- `ls -l ~/ownership_lab/` 결과 캡처
- `groups` 명령어 결과 캡처
- SSH 키 권한 설정 명령어 정리

### 평가 기준

| 항목 | 배점 |
|------|------|
| 그룹 개념 이해 | 25% |
| 소유자/그룹 변경 (chown, chgrp) | 35% |
| 실무 권한 설정 | 40% |

---

## 예상 질문 및 답변

### Q: chown과 chgrp 차이는?
**A**: chown은 소유자(+그룹) 변경, chgrp는 그룹만 변경. `chown :그룹` = `chgrp 그룹`

### Q: 왜 SSH 키는 600이어야 하나요?
**A**: 다른 사용자가 읽을 수 있으면 보안 위험. SSH는 키 파일 권한이 너무 열려있으면 접속을 거부합니다.

### Q: 775와 755 차이는?
**A**: 775는 그룹도 쓰기 가능, 755는 그룹은 읽기/실행만. 팀 공유 디렉토리는 775.

---

## 다음 수업 예고

**Day 6**: (교재 내용에 따라 결정)
