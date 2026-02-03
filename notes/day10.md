# Day 10: 최종 프로젝트

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 주제 | Apache + PHP + MySQL 웹 DB 시스템 구축 |
| 목표 | 2주간 학습 내용 통합 및 실무 적용 |

## 학습 목표

- Apache + PHP + MySQL 연동 웹 시스템 구축
- 데이터 CRUD(생성/조회/수정/삭제) 구현
- 전체 시스템 구성 및 운영 능력 습득

---

## 시간표

| 시간 | 내용 |
|------|------|
| 0.5h | 프로젝트 요구사항 설명 |
| 3.5h | 프로젝트 수행 |
| 1h | 결과 발표 및 피드백 |

---

## 1. 프로젝트 요구사항 (30분)

### 프로젝트 개요

**방명록(Guestbook) 웹 애플리케이션 구축**

- 방문자가 이름과 메시지를 남길 수 있는 웹 페이지
- 작성된 방명록을 목록으로 조회
- Apache + PHP + MySQL 연동

### 시스템 구성도

```
[웹 브라우저] → [Apache2 + PHP] → [MySQL]
      ↓               ↓              ↓
   HTTP 요청       PHP 처리       데이터 저장
```

### 구현 기능

| 기능 | 설명 |
|------|------|
| 조회 (Read) | 방명록 목록 표시 |
| 작성 (Create) | 새 방명록 등록 |
| 삭제 (Delete) | 방명록 삭제 (선택) |

### 평가 기준

| 항목 | 배점 | 세부 내용 |
|------|------|----------|
| Apache 웹 서버 | 15% | 정상 동작, 페이지 접속 |
| PHP 설치/연동 | 15% | PHP 정보 페이지, MySQL 연결 |
| MySQL 데이터베이스 | 25% | DB/테이블 생성, 데이터 저장 |
| 웹 애플리케이션 | 30% | 방명록 CRUD 기능 동작 |
| 시스템 설정 | 10% | 방화벽, 권한 설정 |
| 발표 | 5% | 구축 과정 설명 |

---

## 2. 프로젝트 수행 (3.5시간)

### 프로젝트 경로

```
/var/www/html/guestbook/
```

---

### Step 1: PHP 설치 (15분)

```bash
# 1. PHP 및 MySQL 연동 모듈 설치
sudo apt update
sudo apt install php libapache2-mod-php php-mysql -y

# 2. PHP 버전 확인
php -v

# 3. Apache 재시작
sudo systemctl restart apache2

# 4. PHP 정보 페이지 생성
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php

# 5. 브라우저에서 확인
# http://localhost/info.php
```

---

### Step 2: 데이터베이스 준비 (20분)

```bash
# MySQL 접속
sudo mysql
```

```sql
-- 1. 데이터베이스 생성
CREATE DATABASE guestbook_db;

-- 2. 데이터베이스 선택
USE guestbook_db;

-- 3. 방명록 테이블 생성
CREATE TABLE entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. 테스트 데이터 삽입
INSERT INTO entries (name, message) VALUES ('관리자', '방명록 시스템 테스트입니다.');

-- 5. 데이터 확인
SELECT * FROM entries;

-- 6. 웹 애플리케이션용 사용자 생성
CREATE USER 'guestuser'@'localhost' IDENTIFIED BY 'guest123';
GRANT ALL PRIVILEGES ON guestbook_db.* TO 'guestuser'@'localhost';
FLUSH PRIVILEGES;

-- 7. 종료
EXIT;
```

---

### Step 3: 프로젝트 디렉토리 생성 (5분)

```bash
# 1. 프로젝트 디렉토리 생성
sudo mkdir -p /var/www/html/guestbook

# 2. 권한 설정
sudo chown -R $USER:$USER /var/www/html/guestbook
```

---

### Step 4: 데이터베이스 연결 파일 (10분)

```bash
nano /var/www/html/guestbook/db.php
```

```php
<?php
$host = 'localhost';
$dbname = 'guestbook_db';
$username = 'guestuser';
$password = 'guest123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>
```

---

### Step 5: 메인 페이지 (index.php) (30분)

```bash
nano /var/www/html/guestbook/index.php
```

```php
<?php
require_once 'db.php';

// 방명록 작성 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO entries (name, message) VALUES (?, ?)");
        $stmt->execute([$name, $message]);
    }
}

// 방명록 목록 조회
$stmt = $pdo->query("SELECT * FROM entries ORDER BY created_at DESC");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>방명록</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .entry {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .entry-header {
            font-weight: bold;
            color: #333;
        }
        .entry-date {
            color: #888;
            font-size: 0.9em;
        }
        .entry-message {
            margin-top: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>방명록</h1>

    <div class="form-container">
        <h3>글 남기기</h3>
        <form method="POST">
            <input type="text" name="name" placeholder="이름" required>
            <textarea name="message" placeholder="메시지를 입력하세요" rows="3" required></textarea>
            <button type="submit">등록</button>
        </form>
    </div>

    <h3>방명록 목록 (<?php echo count($entries); ?>개)</h3>

    <?php if (empty($entries)): ?>
        <p>아직 작성된 방명록이 없습니다.</p>
    <?php else: ?>
        <?php foreach ($entries as $entry): ?>
            <div class="entry">
                <div class="entry-header">
                    <?php echo htmlspecialchars($entry['name']); ?>
                </div>
                <div class="entry-date">
                    <?php echo $entry['created_at']; ?>
                </div>
                <div class="entry-message">
                    <?php echo nl2br(htmlspecialchars($entry['message'])); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr>
    <p style="text-align: center; color: #888;">
        Linux System Class - Day 10 Final Project
    </p>
</body>
</html>
```

---

### Step 6: 방화벽 설정 (5분)

```bash
# 방화벽 상태 확인
sudo ufw status

# 필요한 포트 허용
sudo ufw allow 22
sudo ufw allow 80

# 방화벽 활성화 (이미 되어있으면 생략)
sudo ufw enable

# 상태 확인
sudo ufw status
```

---

### Step 7: 테스트 (15분)

```bash
# 1. Apache 상태 확인
sudo systemctl status apache2

# 2. MySQL 상태 확인
sudo systemctl status mysql

# 3. 로컬 테스트
curl http://localhost/guestbook/

# 4. 브라우저에서 확인
# http://localhost/guestbook/
# 또는 http://VM의IP/guestbook/
```

**테스트 항목:**
1. 페이지가 정상적으로 표시되는가?
2. 이름과 메시지를 입력하고 등록 버튼을 누르면 저장되는가?
3. 등록한 방명록이 목록에 나타나는가?

---

### Step 8: 결과 저장 (20분)

```bash
# 홈 디렉토리에 프로젝트 결과 저장
mkdir -p ~/projects/final_project
cd ~/projects/final_project

# 결과 파일 생성
echo "=== Day 10 최종 프로젝트 결과 ===" > result.txt
echo "프로젝트: 방명록 웹 애플리케이션" >> result.txt
echo "작성일: $(date)" >> result.txt
echo "" >> result.txt

# Apache 상태
echo "=== Apache 상태 ===" >> result.txt
systemctl is-active apache2 >> result.txt

# PHP 버전
echo "=== PHP 버전 ===" >> result.txt
php -v | head -1 >> result.txt

# MySQL 상태
echo "=== MySQL 상태 ===" >> result.txt
systemctl is-active mysql >> result.txt

# 데이터베이스 내용
echo "=== 방명록 데이터 ===" >> result.txt
mysql -u guestuser -pguest123 -e "USE guestbook_db; SELECT * FROM entries;" >> result.txt 2>&1

# 방화벽 상태
echo "=== 방화벽 상태 ===" >> result.txt
sudo ufw status >> result.txt

# 프로젝트 파일 목록
echo "=== 프로젝트 파일 ===" >> result.txt
ls -la /var/www/html/guestbook/ >> result.txt

# 결과 확인
cat result.txt

# 프로젝트 파일 복사
cp /var/www/html/guestbook/*.php ./
```

---

### Step 9: Git 제출 (10분)

```bash
cd ~/projects/final_project

# Git 초기화
git init

# 파일 추가
git add result.txt db.php index.php

# 커밋
git commit -m "Day 10 최종 프로젝트: 방명록 웹 애플리케이션"

# 원격 저장소 연결 및 푸시
# git remote add origin https://github.com/사용자명/저장소명.git
# git push -u origin main
```

---

## 3. 결과 발표 및 피드백 (1시간)

### 발표 내용

1. **시스템 구성 설명** (2분)
   - Apache, PHP, MySQL 각각의 역할
   - 연동 방식 설명

2. **시연** (3분)
   - 방명록 페이지 접속
   - 새 글 작성
   - 목록 확인

3. **겪었던 문제와 해결** (2분)
   - 오류 발생 시 어떻게 해결했는지
   - AI 도구 활용 경험

### 발표 체크리스트

- [ ] 웹 페이지 정상 접속
- [ ] 방명록 작성 기능 동작
- [ ] 작성된 글 목록 표시
- [ ] MySQL에 데이터 저장 확인

---

## 완료 체크리스트

### 시스템 설치

- [ ] Apache2 설치 및 실행
- [ ] PHP 설치 및 Apache 연동
- [ ] MySQL 설치 및 실행

### 데이터베이스

- [ ] guestbook_db 데이터베이스 생성
- [ ] entries 테이블 생성
- [ ] guestuser 사용자 생성 및 권한 부여

### 웹 애플리케이션

- [ ] db.php (DB 연결 파일) 작성
- [ ] index.php (메인 페이지) 작성
- [ ] 방명록 조회 기능 동작
- [ ] 방명록 작성 기능 동작

### 보안

- [ ] 방화벽 활성화
- [ ] SSH(22), HTTP(80) 포트 허용

### 제출

- [ ] result.txt 저장
- [ ] 소스코드 Git 제출
- [ ] 발표 준비

---

## 자주 하는 실수 및 해결

| 실수 | 해결 방법 |
|------|-----------|
| PHP 페이지가 다운로드됨 | `sudo apt install libapache2-mod-php` 후 Apache 재시작 |
| DB 연결 오류 | db.php의 사용자명/비밀번호 확인 |
| 페이지 빈 화면 | PHP 오류 확인: `sudo tail /var/log/apache2/error.log` |
| 테이블 없음 오류 | MySQL에서 테이블 생성 확인 |
| 권한 오류 | `sudo chown -R www-data:www-data /var/www/html/guestbook` |

---

## 2주 과정 요약

### 1주차 (Day 1~5)

| Day | 주제 | 핵심 명령어 |
|-----|------|-------------|
| 1 | 환경 구축 | VMware, Ubuntu 설치 |
| 2 | 파일 시스템 | ls, cd, mkdir, rm, cp, mv |
| 3 | 셸 기초 | bash, alias, 리다이렉션, 파이프 |
| 4 | 권한/프로세스 | chmod, chown, ps, kill |
| 5 | 디스크/패키지 | df, du, mount, apt |

### 2주차 (Day 6~10)

| Day | 주제 | 핵심 명령어 |
|-----|------|-------------|
| 6 | 사용자/그룹 | useradd, passwd, groupadd |
| 7 | 시스템 관리 | systemctl, apt, dpkg |
| 8 | 네트워크/SSH | ip, ping, ssh, scp |
| 9 | 서버/보안 | apache2, mysql, ufw |
| 10 | 최종 프로젝트 | 전체 통합 |

---

## 다음 단계 추천

### 실무 연계

1. **AWS EC2 실습**
   - EC2 인스턴스 생성
   - SSH로 접속
   - 웹 서버 구축

2. **라즈베리파이**
   - 데비안 계열 OS
   - SSH 원격 접속
   - ROS2 환경 구축

### 추가 학습

1. **Nginx** - Apache 대안 웹 서버
2. **Docker** - 컨테이너 기반 배포
3. **Git/GitHub** - 버전 관리 심화
4. **쉘 스크립트** - 자동화

---

## 수고하셨습니다!

2주간의 리눅스 시스템 과정을 완료하셨습니다.

배운 내용을 바탕으로:
- AWS EC2에서 서버 운영
- 라즈베리파이에서 ROS2 환경 구축
- 실무에서 리눅스 시스템 관리

를 수행할 수 있는 기초를 갖추셨습니다.

**기억하세요:**
- 모르면 `man 명령어` 또는 AI에게 질문
- 오류 메시지는 해결의 실마리
- 실습이 최고의 학습

화이팅!
