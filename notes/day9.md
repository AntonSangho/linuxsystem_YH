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

> 상세 SQL 문법은 교재 11장 참고

---

## 2. MariaDB 설치 및 실습 (1시간)

### MariaDB 개요

- MySQL에서 파생된 오픈소스 관계형 데이터베이스
- MySQL과 호환성이 높아 기존 MySQL 명령어/도구 사용 가능
- Ubuntu에서는 `apt`로 간편 설치

### 실습 내용

1. **MariaDB 설치**: `apt`를 사용한 설치 및 서비스 시작
2. **DB/테이블 생성**: CREATE DATABASE, CREATE TABLE
3. **데이터 CRUD**:
   - INSERT (데이터 입력)
   - SELECT (데이터 조회)
   - UPDATE (데이터 수정)
   - DELETE (데이터 삭제)
4. **사용자 관리**: 사용자 생성 및 권한 부여

> 상세 설치/실습 절차는 교재 11장 참고

---

## 3. Apache 웹 서버 (1시간)

### Apache 개요

- 세계에서 가장 많이 사용되는 웹 서버 중 하나
- HTTP 요청을 받아 웹 페이지를 응답
- 포트: 80 (HTTP), 443 (HTTPS)

### 실습 내용

1. **Apache 설치**: `apt`를 사용한 설치 및 서비스 시작
2. **외부 접속 설정**: 방화벽 포트 개방, IP 주소 확인
3. **시스템 디렉토리 웹사이트**: `/var/www/html/` 에 웹 페이지 구축
4. **사용자 디렉토리 웹사이트**: `userdir` 모듈 활성화로 사용자별 웹 공간 설정

### Apache 주요 경로

| 경로 | 설명 |
|------|------|
| `/var/www/html/` | 기본 웹 문서 디렉토리 |
| `/etc/apache2/` | 설정 파일 디렉토리 |
| `/etc/apache2/sites-available/` | 가상 호스트 설정 |
| `/var/log/apache2/` | 로그 파일 |

> 상세 설정 절차는 교재 12장 참고

---

## 4. PHP 설치 및 APM 연동 (1시간)

### APM이란?

- **A**pache + **P**HP + **M**ariaDB(MySQL) 의 약자
- 리눅스에서 웹 서비스를 구축하는 가장 대표적인 조합
- PHP가 Apache와 MariaDB 사이에서 동적 웹 페이지를 생성

### 실습 내용

1. **PHP 설치**: PHP 및 관련 모듈 설치 (php, libapache2-mod-php, php-mysql 등)
2. **APM 연동 확인**: PHP 정보 페이지(`phpinfo()`)로 연동 상태 확인
3. **PHP에서 MariaDB 접속 테스트**: 간단한 PHP 스크립트로 DB 연결 확인

> 상세 설치/연동 절차는 교재 16장 참고

---

## 5. gnuboard 5.6.4 설치 (1시간)

### gnuboard란?

- 한국에서 가장 많이 사용되는 PHP 기반 공개 게시판
- APM 환경에서 동작
- 설치가 간편하여 웹 사이트 빠른 구축 가능

### 실습 내용

1. **gnuboard 다운로드**: 공식 사이트에서 gnuboard 5.6.4 다운로드
2. **파일 배치**: Apache 웹 디렉토리에 파일 복사
3. **권한 설정**: 웹 서버(www-data)가 접근 가능하도록 디렉토리/파일 권한 설정
4. **웹 설치**: 브라우저에서 설치 페이지 접속 → DB 정보 입력 → 관리자 설정
5. **동작 확인**: 게시판 접속 및 글 작성 테스트

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
