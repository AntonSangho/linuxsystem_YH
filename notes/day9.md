# Day 9: 웹 서버, 데이터베이스, 보안

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 주제 | Apache2, MySQL, 방화벽(ufw) |
| 실습과제 | Apache + MySQL + 방화벽 통합 실습 |

## 학습 목표

- Apache 웹 서버 설치, 설정, 운영 능력 습득
- MySQL 설치, 데이터베이스/테이블 생성, 사용자 권한 관리
- 방화벽 설정 능력 습득

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1.5h | Apache2 웹 서버 |
| 1.5h | MySQL 데이터베이스 |
| 1h | 방화벽 ufw |
| 1h | 통합 실습 |

---

## 1. Apache2 웹 서버 (1.5시간)

### Apache란?

- 세계에서 가장 많이 사용되는 웹 서버
- HTTP 요청을 받아 웹 페이지를 응답
- 포트: 80 (HTTP), 443 (HTTPS)

### Apache 설치

```bash
# 패키지 업데이트
sudo apt update

# Apache 설치
sudo apt install apache2 -y

# 서비스 시작 및 활성화
sudo systemctl start apache2
sudo systemctl enable apache2

# 상태 확인
sudo systemctl status apache2
```

### 웹 페이지 확인

```bash
# 로컬 테스트
curl http://localhost

# IP 주소 확인
ip addr | grep "inet " | grep -v 127.0.0.1

# 브라우저에서 http://VM의IP주소 접속
```

### Apache 주요 경로

| 경로 | 설명 |
|------|------|
| /var/www/html/ | 기본 웹 문서 디렉토리 |
| /etc/apache2/ | 설정 파일 디렉토리 |
| /etc/apache2/apache2.conf | 메인 설정 파일 |
| /etc/apache2/sites-available/ | 가상 호스트 설정 |
| /var/log/apache2/ | 로그 파일 |

### 기본 웹 페이지 수정

```bash
# 기본 페이지 백업
sudo cp /var/www/html/index.html /var/www/html/index.html.bak

# 새 페이지 작성
sudo nano /var/www/html/index.html
```

```html
<!DOCTYPE html>
<html>
<head>
    <title>My First Web Page</title>
</head>
<body>
    <h1>Hello, Linux Web Server!</h1>
    <p>Apache2 on Ubuntu</p>
</body>
</html>
```

### 가상 호스트 (Virtual Host)

```bash
# 가상 호스트 설정 파일 생성
sudo nano /etc/apache2/sites-available/mysite.conf
```

```apache
<VirtualHost *:80>
    ServerName mysite.local
    DocumentRoot /var/www/mysite

    <Directory /var/www/mysite>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/mysite_error.log
    CustomLog ${APACHE_LOG_DIR}/mysite_access.log combined
</VirtualHost>
```

```bash
# 웹 디렉토리 생성
sudo mkdir -p /var/www/mysite

# 테스트 페이지 생성
echo "<h1>My Site</h1>" | sudo tee /var/www/mysite/index.html

# 가상 호스트 활성화
sudo a2ensite mysite.conf

# 설정 테스트
sudo apache2ctl configtest

# Apache 재시작
sudo systemctl reload apache2
```

### Apache 명령어

```bash
# 설정 테스트
sudo apache2ctl configtest

# 사이트 활성화/비활성화
sudo a2ensite 사이트명
sudo a2dissite 사이트명

# 모듈 활성화/비활성화
sudo a2enmod 모듈명
sudo a2dismod 모듈명
```

---

## 2. MySQL 데이터베이스 (1.5시간)

### MySQL 설치

```bash
# MySQL 서버 설치
sudo apt install mysql-server -y

# 서비스 시작 및 활성화
sudo systemctl start mysql
sudo systemctl enable mysql

# 상태 확인
sudo systemctl status mysql
```

### MySQL 보안 설정

```bash
# 보안 설정 스크립트 실행
sudo mysql_secure_installation

# 질문에 대한 권장 답변:
# - VALIDATE PASSWORD: n (테스트 환경)
# - root 비밀번호: 설정
# - Remove anonymous users: y
# - Disallow root login remotely: y
# - Remove test database: y
# - Reload privilege tables: y
```

### MySQL 접속

```bash
# root로 접속 (Ubuntu 기본)
sudo mysql

# 또는 비밀번호로 접속
mysql -u root -p
```

### 기본 SQL 명령어

```sql
-- 데이터베이스 목록
SHOW DATABASES;

-- 데이터베이스 생성
CREATE DATABASE mydb;

-- 데이터베이스 선택
USE mydb;

-- 테이블 생성
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 테이블 목록
SHOW TABLES;

-- 테이블 구조 확인
DESCRIBE users;

-- 데이터 삽입
INSERT INTO users (name, email) VALUES ('홍길동', 'hong@example.com');
INSERT INTO users (name, email) VALUES ('김철수', 'kim@example.com');

-- 데이터 조회
SELECT * FROM users;

-- 데이터 수정
UPDATE users SET email = 'hong2@example.com' WHERE name = '홍길동';

-- 데이터 삭제
DELETE FROM users WHERE id = 2;

-- MySQL 종료
EXIT;
```

### MySQL 사용자 관리

```sql
-- 현재 사용자 확인
SELECT User, Host FROM mysql.user;

-- 새 사용자 생성
CREATE USER 'webuser'@'localhost' IDENTIFIED BY 'password123';

-- 권한 부여 (특정 DB에 대해)
GRANT ALL PRIVILEGES ON mydb.* TO 'webuser'@'localhost';

-- 권한 적용
FLUSH PRIVILEGES;

-- 사용자 삭제
DROP USER 'webuser'@'localhost';
```

### 실습: 데이터베이스 생성

```bash
# MySQL 접속
sudo mysql
```

```sql
-- 1. 데이터베이스 생성
CREATE DATABASE testdb;

-- 2. 데이터베이스 선택
USE testdb;

-- 3. 테이블 생성
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT,
    stock INT DEFAULT 0
);

-- 4. 데이터 삽입
INSERT INTO products (name, price, stock) VALUES ('노트북', 1500000, 10);
INSERT INTO products (name, price, stock) VALUES ('마우스', 30000, 50);
INSERT INTO products (name, price, stock) VALUES ('키보드', 80000, 30);

-- 5. 데이터 조회
SELECT * FROM products;

-- 6. 종료
EXIT;
```

---

## 3. 방화벽 ufw (1시간)

### ufw란?

- Uncomplicated Firewall: Ubuntu 기본 방화벽
- iptables를 쉽게 관리하는 도구
- 포트 기반 접근 제어

### ufw 기본 명령어

```bash
# 상태 확인
sudo ufw status

# 자세한 상태
sudo ufw status verbose

# 방화벽 활성화
sudo ufw enable

# 방화벽 비활성화
sudo ufw disable

# 규칙 초기화
sudo ufw reset
```

### 포트 허용/차단

```bash
# 포트 허용
sudo ufw allow 22        # SSH
sudo ufw allow 80        # HTTP
sudo ufw allow 443       # HTTPS
sudo ufw allow 3306      # MySQL

# 포트 차단
sudo ufw deny 23         # Telnet

# 서비스명으로 허용
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https

# 특정 IP에서만 허용
sudo ufw allow from 192.168.1.100 to any port 22
```

### 규칙 관리

```bash
# 규칙 목록 (번호 포함)
sudo ufw status numbered

# 규칙 삭제 (번호로)
sudo ufw delete 2

# 규칙 삭제 (내용으로)
sudo ufw delete allow 80
```

### 기본 정책 설정

```bash
# 들어오는 연결: 기본 차단
sudo ufw default deny incoming

# 나가는 연결: 기본 허용
sudo ufw default allow outgoing
```

### 실습: 방화벽 설정

```bash
# 1. 현재 상태 확인
sudo ufw status

# 2. 방화벽 활성화
sudo ufw enable

# 3. SSH 허용 (필수! 원격 접속용)
sudo ufw allow 22

# 4. HTTP 허용 (웹 서버용)
sudo ufw allow 80

# 5. 상태 확인
sudo ufw status

# 6. 규칙 번호로 보기
sudo ufw status numbered
```

---

## 4. 실습과제 9: Apache + MySQL + 방화벽 통합 (1시간)

### 과제 목표

- Apache 웹 서버 설정
- MySQL 데이터베이스 생성
- 방화벽으로 필요한 포트만 개방

### 실습 환경

```
/home/user1/projects/server_lab/
```

---

### Part 1: 실습 준비 (5분)

```bash
# 실습 디렉토리 생성
mkdir -p ~/projects/server_lab
cd ~/projects/server_lab
```

---

### Part 2: Apache 설정 (15분)

```bash
# 1. Apache 상태 확인
sudo systemctl status apache2

# 2. 웹 페이지 생성
sudo nano /var/www/html/mypage.html
```

```html
<!DOCTYPE html>
<html>
<head>
    <title>Day 9 Lab</title>
</head>
<body>
    <h1>Apache + MySQL + UFW</h1>
    <p>서버 통합 실습 완료!</p>
</body>
</html>
```

```bash
# 3. 웹 페이지 확인
curl http://localhost/mypage.html
```

---

### Part 3: MySQL 설정 (20분)

```bash
# 1. MySQL 접속
sudo mysql
```

```sql
-- 2. 데이터베이스 생성
CREATE DATABASE webdb;

-- 3. 데이터베이스 선택
USE webdb;

-- 4. 테이블 생성
CREATE TABLE visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. 데이터 삽입
INSERT INTO visitors (name) VALUES ('Guest1');
INSERT INTO visitors (name) VALUES ('Guest2');

-- 6. 데이터 확인
SELECT * FROM visitors;

-- 7. 웹 사용자 생성
CREATE USER 'webuser'@'localhost' IDENTIFIED BY 'webpass123';
GRANT ALL PRIVILEGES ON webdb.* TO 'webuser'@'localhost';
FLUSH PRIVILEGES;

-- 8. 종료
EXIT;
```

```bash
# 9. 새 사용자로 접속 테스트
mysql -u webuser -pwebpass123 -e "USE webdb; SELECT * FROM visitors;"
```

---

### Part 4: 방화벽 설정 (10분)

```bash
# 1. 방화벽 상태 확인
sudo ufw status

# 2. 방화벽 활성화 (이미 활성화되어 있으면 생략)
sudo ufw enable

# 3. 필요한 포트 허용
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP

# 4. 상태 확인
sudo ufw status numbered

# 5. 웹 접속 테스트
curl http://localhost/mypage.html
```

---

### Part 5: 결과 저장 (10분)

```bash
cd ~/projects/server_lab

# 결과 파일 생성
echo "=== Day 9 서버 통합 실습 결과 ===" > result.txt

# Apache 상태
echo "=== Apache 상태 ===" >> result.txt
systemctl is-active apache2 >> result.txt

# MySQL 상태
echo "=== MySQL 상태 ===" >> result.txt
systemctl is-active mysql >> result.txt

# MySQL 데이터 확인
echo "=== MySQL 데이터 ===" >> result.txt
mysql -u webuser -pwebpass123 -e "USE webdb; SELECT * FROM visitors;" >> result.txt 2>&1

# 방화벽 상태
echo "=== 방화벽 상태 ===" >> result.txt
sudo ufw status >> result.txt

# 결과 확인
cat result.txt
```

---

### Part 6: Git 제출 (10분)

```bash
cd ~/projects/server_lab

# Git 초기화
git init

# 파일 추가
git add result.txt

# 커밋
git commit -m "Day 9 실습: Apache + MySQL + 방화벽"

# 원격 저장소 연결 및 푸시
# git remote add origin https://github.com/사용자명/저장소명.git
# git push -u origin main
```

---

### 완료 체크리스트

- [ ] Apache 서비스 실행 확인
- [ ] 웹 페이지(mypage.html) 생성 및 접속
- [ ] MySQL 데이터베이스(webdb) 생성
- [ ] MySQL 테이블(visitors) 생성 및 데이터 삽입
- [ ] MySQL 사용자(webuser) 생성
- [ ] 방화벽 활성화
- [ ] SSH(22), HTTP(80) 포트 허용
- [ ] result.txt 저장
- [ ] Git 제출

---

### 자주 하는 실수

| 실수 | 해결 방법 |
|------|-----------|
| Apache 시작 안 됨 | `sudo systemctl start apache2` |
| MySQL 접속 오류 | `sudo mysql` (Ubuntu에서는 sudo 필요) |
| 방화벽으로 SSH 차단 | VM 콘솔에서 `sudo ufw allow 22` |
| 웹 접속 안 됨 | `sudo ufw allow 80` 확인 |
| 비밀번호 오류 | 정확한 비밀번호 입력 확인 |

---

### 평가 기준

| 항목 | 배점 |
|------|------|
| Apache 웹 서버 설정 | 25% |
| MySQL DB/테이블/사용자 생성 | 35% |
| 방화벽 포트 설정 | 20% |
| 결과 저장 및 Git 제출 | 20% |

---

## 핵심 명령어 정리

### Apache

```bash
sudo systemctl status apache2   # 상태 확인
sudo systemctl restart apache2  # 재시작
sudo a2ensite 사이트명           # 사이트 활성화
sudo apache2ctl configtest      # 설정 테스트
```

### MySQL

```bash
sudo mysql                      # MySQL 접속
CREATE DATABASE 이름;            # DB 생성
CREATE TABLE 이름 (...);        # 테이블 생성
CREATE USER '사용자'@'localhost' IDENTIFIED BY '비밀번호';
GRANT ALL PRIVILEGES ON DB.* TO '사용자'@'localhost';
```

### 방화벽 (ufw)

```bash
sudo ufw status                 # 상태 확인
sudo ufw enable                 # 활성화
sudo ufw allow 포트번호          # 포트 허용
sudo ufw status numbered        # 규칙 번호로 보기
```

---

## 예상 질문 및 답변

### Q: Apache와 Nginx 차이는?
**A**: Apache는 프로세스/스레드 기반으로 동적 콘텐츠에 강함. Nginx는 이벤트 기반으로 정적 콘텐츠와 동시 접속에 강함. 둘 다 많이 사용됨.

### Q: MySQL root 비밀번호를 잊었으면?
**A**: `sudo mysql`로 접속 후 `ALTER USER 'root'@'localhost' IDENTIFIED BY '새비밀번호';`로 변경.

### Q: 방화벽 설정 잘못해서 SSH 접속이 끊겼으면?
**A**: VM 콘솔(VMware 화면)에서 직접 로그인 후 `sudo ufw allow 22` 또는 `sudo ufw disable`.

---

## 다음 수업 예고

**Day 10**: 최종 프로젝트
- Apache + PHP + MySQL 웹 DB 시스템 구축
- 전체 과정 복습 및 통합
- 결과 발표
