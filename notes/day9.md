# Day 9: Apache + MariaDB + PHP + gnuboard

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 주제 | 데이터베이스, MariaDB, Apache, PHP, gnuboard |
| 교재 범위 | 11장(데이터베이스), 12장(웹 서버), 16장(APM+gnuboard) |

## 학습 목표

1. 데이터베이스에 대해 설명할 수 있다
2. 관계형 데이터베이스와 관련된 용어를 이해하고 설명할 수 있다
3. 기본적인 SQL 문법을 이해하고 사용할 수 있다
4. MariaDB를 설치할 수 있다
5. MariaDB에서 DB/테이블을 생성하고 데이터를 입력 및 검색할 수 있다
6. Apache 웹 서버를 설치하고 외부에서 접속하도록 할 수 있다
7. 시스템 디렉토리와 사용자 디렉토리에 웹 사이트를 구축할 수 있다
8. APM(Apache + PHP + MariaDB)이 연동되도록 설치할 수 있다
9. 공개 게시판(gnuboard 5.6.4)을 설치하고 웹 사이트에 연결할 수 있다

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1h | 데이터베이스 개념, 관계형 DB 용어, SQL 기초 |
| 1h | MariaDB 설치 및 실습 (DB/테이블 생성, 데이터 CRUD) |
| 1h | Apache 설치, 외부 접속 설정, 시스템/사용자 디렉토리 웹사이트 |
| 1h | PHP 설치, APM 연동 확인 |
| 1h | gnuboard 5.6.4 설치 및 웹 사이트 연결 |

---

## 1. 데이터베이스 개념 및 SQL 기초 (1시간)

### 데이터베이스란?

- 데이터를 체계적으로 저장하고 관리하는 시스템
- DBMS(Database Management System): 데이터베이스를 관리하는 소프트웨어

### 관계형 데이터베이스 용어

| 용어 | 설명 |
|------|------|
| 테이블 (Table) | 데이터를 저장하는 2차원 표 |
| 행 (Row / Record) | 하나의 데이터 항목 |
| 열 (Column / Field) | 데이터의 속성 |
| 기본키 (Primary Key) | 각 행을 고유하게 식별하는 값 |
| 외래키 (Foreign Key) | 다른 테이블의 기본키를 참조하는 키 |
| SQL | 데이터베이스를 조작하는 표준 언어 |

### SQL 기초

- **DDL (Data Definition Language)**: CREATE, ALTER, DROP
- **DML (Data Manipulation Language)**: SELECT, INSERT, UPDATE, DELETE
- **DCL (Data Control Language)**: GRANT, REVOKE

### SQL 기본 문법 예시

```sql
-- 데이터베이스 생성
CREATE DATABASE shop_db;

-- 데이터베이스 선택
USE shop_db;

-- 테이블 생성
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    quantity INT DEFAULT 0
);

-- 데이터 입력 (INSERT)
INSERT INTO products (name, price, quantity) VALUES ('키보드', 50000, 10);
INSERT INTO products (name, price, quantity) VALUES ('마우스', 30000, 20);
INSERT INTO products (name, price, quantity) VALUES ('모니터', 250000, 5);

-- 데이터 조회 (SELECT)
SELECT * FROM products;                        -- 전체 조회
SELECT name, price FROM products;              -- 특정 열만 조회
SELECT * FROM products WHERE price >= 50000;   -- 조건 조회
SELECT * FROM products ORDER BY price DESC;    -- 정렬

-- 데이터 수정 (UPDATE)
UPDATE products SET price = 45000 WHERE name = '키보드';

-- 데이터 삭제 (DELETE)
DELETE FROM products WHERE name = '마우스';
```

> 상세 SQL 문법은 교재 11장 참고

---

## 2. MariaDB 설치 및 실습 (1시간)

### MariaDB 개요

- MySQL에서 파생된 오픈소스 관계형 데이터베이스
- MySQL과 호환성이 높아 기존 MySQL 명령어/도구 사용 가능
- Ubuntu에서는 `apt`로 간편 설치

### 실습: MariaDB 설치

```bash
# MariaDB 설치
sudo apt install mariadb-server mariadb-client -y

# 서비스 상태 확인
sudo systemctl status mariadb

# MariaDB 접속 (Ubuntu에서는 sudo 필요)
sudo mysql
```

### 실습: 데이터베이스 및 테이블 생성

```sql
-- 현재 데이터베이스 목록 확인
SHOW DATABASES;

-- 데이터베이스 생성
CREATE DATABASE shop_db;

-- 데이터베이스 선택
USE shop_db;

-- 테이블 생성
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    quantity INT DEFAULT 0
);

-- 테이블 구조 확인
DESC products;
```

### 실습: 데이터 CRUD

```sql
-- INSERT: 데이터 입력
INSERT INTO products (name, price, quantity) VALUES ('키보드', 50000, 10);
INSERT INTO products (name, price, quantity) VALUES ('마우스', 30000, 20);
INSERT INTO products (name, price, quantity) VALUES ('모니터', 250000, 5);

-- SELECT: 데이터 조회
SELECT * FROM products;
SELECT name, price FROM products WHERE price >= 50000;

-- UPDATE: 데이터 수정
UPDATE products SET price = 45000 WHERE name = '키보드';

-- DELETE: 데이터 삭제
DELETE FROM products WHERE name = '마우스';

-- 결과 확인
SELECT * FROM products;
```

### 실습: 사용자 생성 및 권한 부여

```sql
-- 사용자 생성
CREATE USER 'webuser'@'localhost' IDENTIFIED BY 'password1234';

-- 특정 DB에 대한 모든 권한 부여
GRANT ALL PRIVILEGES ON shop_db.* TO 'webuser'@'localhost';

-- 권한 적용
FLUSH PRIVILEGES;

-- MariaDB 종료
EXIT;
```

```bash
# 생성한 사용자로 접속 테스트
mysql -u webuser -p
# 비밀번호 입력: password1234
```

> 상세 설치/실습 절차는 교재 11장 참고

---

## 3. Apache 웹 서버 (1시간)

### Apache 개요

- 세계에서 가장 많이 사용되는 웹 서버 중 하나
- HTTP 요청을 받아 웹 페이지를 응답
- 포트: 80 (HTTP), 443 (HTTPS)

### Apache 주요 경로

| 경로 | 설명 |
|------|------|
| `/var/www/html/` | 기본 웹 문서 디렉토리 |
| `/etc/apache2/` | 설정 파일 디렉토리 |
| `/etc/apache2/sites-available/` | 가상 호스트 설정 |
| `/var/log/apache2/` | 로그 파일 |

### 실습: Apache 설치 및 시작

```bash
# Apache 설치
sudo apt install apache2 -y

# 서비스 상태 확인
sudo systemctl status apache2

# 방화벽에서 HTTP(80번 포트) 허용
sudo ufw allow 80/tcp

# 서버 IP 주소 확인
ip addr show | grep inet
```

설치 후 브라우저에서 `http://서버IP주소` 접속 → Apache 기본 페이지가 보이면 성공

### 실습: 시스템 디렉토리 웹사이트

```bash
# 기본 웹 페이지 편집
sudo nano /var/www/html/index.html
```

```html
<!DOCTYPE html>
<html>
<head><title>My First Web Page</title></head>
<body>
    <h1>Day 9 Apache 실습</h1>
    <p>Apache 웹 서버가 정상 동작합니다!</p>
</body>
</html>
```

브라우저에서 `http://서버IP주소` 접속하여 변경된 페이지 확인

### 실습: 사용자 디렉토리 웹사이트

```bash
# userdir 모듈 활성화
sudo a2enmod userdir

# Apache 재시작
sudo systemctl restart apache2

# 사용자 홈 디렉토리에 public_html 폴더 생성
mkdir ~/public_html
chmod 755 ~/public_html

# 사용자 웹 페이지 생성
nano ~/public_html/index.html
```

```html
<!DOCTYPE html>
<html>
<head><title>User Page</title></head>
<body>
    <h1>user1의 개인 웹 페이지</h1>
</body>
</html>
```

브라우저에서 `http://서버IP주소/~user1/` 접속하여 확인

> 상세 설정 절차는 교재 12장 참고

---

## 4. PHP 설치 및 APM 연동 (1시간)

### APM이란?

- **A**pache + **P**HP + **M**ariaDB(MySQL) 의 약자
- 리눅스에서 웹 서비스를 구축하는 가장 대표적인 조합
- PHP가 Apache와 MariaDB 사이에서 동적 웹 페이지를 생성

### 실습: PHP 설치

```bash
# PHP 및 관련 모듈 설치
sudo apt install php libapache2-mod-php php-mysql -y

# Apache 재시작 (PHP 모듈 적용)
sudo systemctl restart apache2

# PHP 버전 확인
php -v
```

### 실습: APM 연동 확인 (phpinfo)

```bash
# PHP 정보 페이지 생성
sudo nano /var/www/html/phpinfo.php
```

```php
<?php
    phpinfo();
?>
```

브라우저에서 `http://서버IP주소/phpinfo.php` 접속 → PHP 정보 페이지가 표시되면 APM 연동 성공

> **확인 포인트**: PHP 정보 페이지에서 `mysqli` 항목이 있으면 PHP-MariaDB 연동 정상

### 실습: PHP에서 MariaDB 접속 테스트

```bash
sudo nano /var/www/html/db_test.php
```

```php
<?php
    $conn = mysqli_connect("localhost", "webuser", "password1234", "shop_db");

    if ($conn) {
        echo "<h2>MariaDB 연결 성공!</h2>";

        $result = mysqli_query($conn, "SELECT * FROM products");

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>상품명</th><th>가격</th><th>수량</th></tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        mysqli_close($conn);
    } else {
        echo "<h2>연결 실패!</h2>";
        echo "오류: " . mysqli_connect_error();
    }
?>
```

브라우저에서 `http://서버IP주소/db_test.php` 접속 → 상품 목록이 테이블로 표시되면 성공

> **보안 주의**: 실습 완료 후 `phpinfo.php`, `db_test.php`는 삭제하는 것이 좋음
>
> ```bash
> sudo rm /var/www/html/phpinfo.php /var/www/html/db_test.php
> ```

> 상세 설치/연동 절차는 교재 16장 참고

---

## 5. gnuboard 5.6.4 설치 (1시간)

### gnuboard란?

- 한국에서 가장 많이 사용되는 PHP 기반 공개 게시판
- APM 환경에서 동작
- 설치가 간편하여 웹 사이트 빠른 구축 가능
- 워드프레스와 비슷한 역할 (CMS: Content Management System)

### APM + gnuboard 동작 구조

```
사용자(브라우저)
    ↓ HTTP 요청
Apache (웹 서버, 포트 80)
    ↓ .php 파일 처리 요청
PHP (스크립트 엔진)
    ↓ DB 조회/저장 요청
MariaDB (데이터베이스)
    ↓ 결과 반환
PHP → Apache → 사용자에게 HTML 응답
```

- **Apache**: 사용자의 요청을 받아 PHP에게 전달하는 역할
- **PHP**: gnuboard의 코드를 실행하고, DB에서 데이터를 가져와 HTML을 생성
- **MariaDB**: 게시글, 회원 정보 등 데이터를 저장/관리

### 웹 애플리케이션 배포 개념

gnuboard 설치는 **웹 애플리케이션 배포**의 기본 과정을 경험하는 것:

| 단계 | 설명 | 핵심 개념 |
|------|------|-----------|
| 1. 파일 배치 | gnuboard 소스를 웹 디렉토리에 복사 | 웹 루트(`/var/www/html/`) |
| 2. 권한 설정 | 웹 서버(www-data)가 읽기/쓰기 가능하도록 | 리눅스 파일 권한 (Day 4 복습) |
| 3. DB 연결 | gnuboard가 MariaDB에 접근할 수 있도록 설정 | DB 호스트, 사용자, 비밀번호, DB명 |
| 4. 웹 설치 | 브라우저에서 설치 마법사 실행 | 웹 기반 설치 인터페이스 |

### gnuboard 설치 시 필요한 정보

| 항목 | 설명 |
|------|------|
| DB Host | MariaDB가 설치된 서버 주소 (보통 `localhost`) |
| DB User | MariaDB 사용자 이름 (gnuboard 전용 사용자 권장) |
| DB Password | 해당 사용자 비밀번호 |
| DB Name | gnuboard가 사용할 데이터베이스 이름 |
| 관리자 ID/PW | gnuboard 관리자 계정 |

### 실습 순서

1. **gnuboard용 DB 준비**: MariaDB에서 전용 DB와 사용자 생성
2. **gnuboard 다운로드**: 공식 사이트에서 gnuboard 5.6.4 다운로드
3. **파일 배치**: Apache 웹 디렉토리(`/var/www/html/`)에 파일 복사
4. **권한 설정**: 웹 서버(www-data)가 접근 가능하도록 디렉토리/파일 권한 설정
5. **웹 설치**: 브라우저에서 설치 페이지 접속 → DB 정보 입력 → 관리자 설정
6. **동작 확인**: 게시판 접속 및 글 작성 테스트

> 상세 설치 절차는 교재 16장 참고

---

## 핵심 개념 정리

### 데이터베이스

| 항목 | 내용 |
|------|------|
| DBMS | 데이터베이스 관리 시스템 (MariaDB, MySQL, PostgreSQL 등) |
| SQL | 데이터베이스 조작 언어 (SELECT, INSERT, UPDATE, DELETE) |
| 관계형 DB | 테이블 간 관계를 정의하여 데이터를 관리하는 방식 |

### 웹 서버

| 항목 | 내용 |
|------|------|
| Apache | HTTP 요청 처리, 정적/동적 웹 페이지 제공 |
| PHP | 서버 측 스크립트 언어, 동적 웹 페이지 생성 |
| APM | Apache + PHP + MariaDB 연동 웹 서비스 환경 |
| gnuboard | PHP 기반 공개 게시판, APM 환경에서 동작 |

---

## 자주 하는 실수 및 해결

| 실수 | 해결 방법 |
|------|-----------|
| MariaDB 접속 오류 | `sudo mysql`로 접속 (Ubuntu에서는 sudo 필요) |
| Apache 시작 안 됨 | `sudo systemctl start apache2` 후 status 확인 |
| PHP 페이지가 다운로드됨 | `libapache2-mod-php` 설치 후 Apache 재시작 |
| gnuboard 권한 오류 | `data/` 디렉토리에 `chmod 707` 또는 소유자 변경 |
| DB 연결 오류 | gnuboard 설치 시 DB 정보(호스트, 사용자, 비밀번호) 재확인 |

---

## 다음 수업 예고

**Day 10**: 웹 서비스 운영/관리 실습
- Day 9에서 구축한 환경의 운영/관리
- 서비스 관리, 로그 분석, 백업/복원
- 트러블슈팅 실습
- 운영 보고서 작성 및 발표
