# Day 7 응용 실습: 나의 Linux 학습 일지 웹사이트 만들기

## 실습 개요

| 항목 | 내용 |
|------|------|
| 목표 | Nginx를 활용하여 HTML 웹사이트 만들기 |
| 연관 수업 | Day 7 (시스템 관리, nginx 설치 및 서비스 관리) |
| 환경 | Ubuntu (VM), VSCode |
| 소요 시간 | 약 60분 |
| 난이도 | ★★☆☆☆ (초급) |

### 전제 조건

- Day 7 기본 실습 완료 (nginx 설치됨)
- VSCode 설치됨

---

## 실습 목표

**"나의 Linux 학습 일지"** 웹사이트를 만들어 Nginx로 배포합니다.

| 파일 | 설명 |
|------|------|
| `index.html` | 메인 페이지 (학습 일지 목록) |
| `day1.html` | Day 1 학습 내용 정리 |

완성하면 브라우저에서 직접 만든 웹사이트를 볼 수 있습니다!

---

## Part 1: 실습 준비 (5분)

### 1.1 실습 디렉토리 생성

```bash
# 실습 디렉토리 생성
mkdir -p ~/projects/linux_diary
cd ~/projects/linux_diary
```

### 1.2 nginx 실행 확인

```bash
# nginx 상태 확인
sudo systemctl status nginx

# 실행 중이 아니면 시작
sudo systemctl start nginx
```

### 1.3 VSCode로 폴더 열기

```bash
# VSCode로 실습 폴더 열기
code ~/projects/linux_diary
```

> **참고**: VSCode가 열리면 왼쪽 파일 탐색기에서 `linux_diary` 폴더가 보입니다.

---

## Part 2: 메인 페이지 만들기 (15분)

### 2.1 index.html 파일 생성

VSCode에서 새 파일을 만듭니다:
1. `Ctrl + N` (새 파일)
2. `Ctrl + S` (저장) → 파일 이름: `index.html`

### 2.2 HTML 코드 작성

아래 코드를 **복사**하여 `index.html`에 **붙여넣기** 하세요:

```html
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>나의 Linux 학습 일지</title>
    <style>
        body {
            font-family: 'Malgun Gothic', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .intro {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .day-list {
            list-style: none;
            padding: 0;
        }
        .day-list li {
            background-color: white;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }
        .day-list a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
        }
        .day-list a:hover {
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>나의 Linux 학습 일지</h1>

    <div class="intro">
        <p>안녕하세요! 이 웹사이트는 Linux 수업에서 배운 내용을 정리한 학습 일지입니다.</p>
        <p>Nginx 웹서버로 직접 만든 나의 첫 번째 웹사이트입니다!</p>
    </div>

    <h2>학습 일지 목록</h2>
    <ul class="day-list">
        <li><a href="day1.html">Day 1 - 리눅스 기본 명령어</a></li>
        <li>Day 2 - Vim 에디터 (준비 중)</li>
        <li>Day 3 - 셸과 환경변수 (준비 중)</li>
        <li>Day 4 - 파일 권한 (준비 중)</li>
        <li>Day 5 - 프로세스 관리 (준비 중)</li>
        <li>Day 6 - 디스크 관리 (준비 중)</li>
        <li>Day 7 - 시스템 관리 (준비 중)</li>
    </ul>

    <div class="footer">
        <p>만든 날짜: 2024년</p>
        <p>Powered by Nginx</p>
    </div>
</body>
</html>
```

### 2.3 파일 저장

`Ctrl + S`를 눌러 저장합니다.

**예상 결과:** VSCode에서 `index.html` 파일이 저장됨

---

## Part 3: Day 1 학습 일지 페이지 만들기 (10분)

### 3.1 day1.html 파일 생성

VSCode에서 새 파일을 만듭니다:
1. `Ctrl + N` (새 파일)
2. `Ctrl + S` (저장) → 파일 이름: `day1.html`

### 3.2 HTML 코드 작성

아래 코드를 **복사**하여 `day1.html`에 **붙여넣기** 하세요:

```html
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Day 1 - 리눅스 기본 명령어</title>
    <style>
        body {
            font-family: 'Malgun Gothic', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 10px;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .command-box {
            background-color: #1e1e1e;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Consolas', monospace;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        .back-link {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
        .note {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <h1>Day 1 - 리눅스 기본 명령어</h1>

    <div class="content">
        <h2>오늘 배운 명령어</h2>

        <table>
            <tr>
                <th>명령어</th>
                <th>설명</th>
                <th>예시</th>
            </tr>
            <tr>
                <td><code>ls</code></td>
                <td>파일/폴더 목록 보기</td>
                <td>ls -la</td>
            </tr>
            <tr>
                <td><code>cd</code></td>
                <td>디렉토리 이동</td>
                <td>cd /home</td>
            </tr>
            <tr>
                <td><code>pwd</code></td>
                <td>현재 위치 확인</td>
                <td>pwd</td>
            </tr>
            <tr>
                <td><code>mkdir</code></td>
                <td>폴더 만들기</td>
                <td>mkdir test</td>
            </tr>
            <tr>
                <td><code>touch</code></td>
                <td>빈 파일 만들기</td>
                <td>touch file.txt</td>
            </tr>
            <tr>
                <td><code>cat</code></td>
                <td>파일 내용 보기</td>
                <td>cat file.txt</td>
            </tr>
        </table>

        <h2>실습한 내용</h2>

        <p>터미널에서 다음 명령어를 실행했습니다:</p>

        <div class="command-box">
$ ls -la<br>
$ cd ~<br>
$ pwd<br>
$ mkdir practice<br>
$ cd practice<br>
$ touch hello.txt
        </div>

        <div class="note">
            <strong>TIP:</strong> <code>ls -la</code>에서 <code>-l</code>은 자세히 보기, <code>-a</code>는 숨김 파일 포함입니다.
        </div>

        <h2>느낀점</h2>
        <p>처음에는 명령어가 낯설었지만, 직접 실습하니까 점점 익숙해졌습니다.</p>
        <p>특히 <code>Tab</code> 키로 자동완성 되는 게 편리했습니다!</p>
    </div>

    <a href="index.html" class="back-link">← 메인으로 돌아가기</a>
</body>
</html>
```

### 3.3 파일 저장

`Ctrl + S`를 눌러 저장합니다.

**예상 결과:** VSCode에서 `day1.html` 파일이 저장됨

---

## Part 4: 테마 선택하기 (10분)

### 4.1 원하는 테마 선택

아래 3가지 테마 중 하나를 선택하세요. 선택한 테마의 CSS를 `index.html`의 `<style>` 부분과 교체합니다.

---

### 테마 A: 터미널 스타일 (해커 느낌)

```html
<style>
    body {
        font-family: 'Consolas', monospace;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #0d1117;
        color: #00ff00;
    }
    h1 {
        color: #00ff00;
        text-align: center;
        border-bottom: 3px solid #00ff00;
        padding-bottom: 10px;
    }
    h2 {
        color: #00ff00;
    }
    .intro {
        background-color: #161b22;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #30363d;
    }
    .day-list {
        list-style: none;
        padding: 0;
    }
    .day-list li {
        background-color: #161b22;
        margin: 10px 0;
        padding: 15px;
        border-radius: 5px;
        border-left: 5px solid #00ff00;
    }
    .day-list a {
        color: #58a6ff;
        text-decoration: none;
        font-size: 18px;
    }
    .day-list a:hover {
        color: #00ff00;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        color: #8b949e;
    }
</style>
```

---

### 테마 B: 깔끔한 스타일 (미니멀)

```html
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        color: #333;
    }
    h1 {
        color: #2c3e50;
        text-align: center;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }
    h2 {
        color: #2c3e50;
    }
    .intro {
        background-color: #ecf0f1;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .day-list {
        list-style: none;
        padding: 0;
    }
    .day-list li {
        background-color: #ecf0f1;
        margin: 10px 0;
        padding: 15px;
        border-radius: 5px;
        border-left: 5px solid #3498db;
    }
    .day-list a {
        color: #3498db;
        text-decoration: none;
        font-size: 18px;
    }
    .day-list a:hover {
        color: #2980b9;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        color: #7f8c8d;
    }
</style>
```

---

### 테마 C: 노트 스타일 (따뜻한 느낌)

```html
<style>
    body {
        font-family: 'Malgun Gothic', sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fdf6e3;
        color: #5c4b37;
    }
    h1 {
        color: #8b4513;
        text-align: center;
        border-bottom: 3px solid #daa520;
        padding-bottom: 10px;
    }
    h2 {
        color: #8b4513;
    }
    .intro {
        background-color: #fff8dc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 2px dashed #daa520;
    }
    .day-list {
        list-style: none;
        padding: 0;
    }
    .day-list li {
        background-color: #fff8dc;
        margin: 10px 0;
        padding: 15px;
        border-radius: 5px;
        border-left: 5px solid #daa520;
    }
    .day-list a {
        color: #cd853f;
        text-decoration: none;
        font-size: 18px;
    }
    .day-list a:hover {
        color: #8b4513;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        color: #a0826d;
    }
</style>
```

### 4.2 테마 적용 방법

1. 위 테마 중 하나의 `<style>...</style>` 코드를 복사
2. `index.html`에서 기존 `<style>...</style>` 부분을 선택
3. 복사한 코드로 교체
4. `Ctrl + S`로 저장

---

## Part 5: Nginx에 배포하기 (10분)

### 5.1 파일 복사

작성한 HTML 파일들을 Nginx 웹 디렉토리로 복사합니다:

```bash
# 파일 복사
sudo cp ~/projects/linux_diary/index.html /var/www/html/
sudo cp ~/projects/linux_diary/day1.html /var/www/html/

# 복사 확인
ls -la /var/www/html/
```

**예상 결과:**
```
-rw-r--r-- 1 root root 2345  ... index.html
-rw-r--r-- 1 root root 3456  ... day1.html
```

### 5.2 Nginx 재시작

```bash
# Nginx 재시작
sudo systemctl restart nginx

# 상태 확인
sudo systemctl status nginx
```

### 5.3 웹사이트 확인

브라우저를 열고 다음 주소로 접속합니다:

```
http://localhost
```

또는 터미널에서 확인:

```bash
# 메인 페이지 확인
curl http://localhost | head -20

# VM의 IP 주소 확인 (다른 컴퓨터에서 접속할 때)
ip addr | grep "inet "
```

### 5.4 페이지 이동 테스트

1. 메인 페이지에서 **"Day 1 - 리눅스 기본 명령어"** 링크 클릭
2. Day 1 페이지가 표시되는지 확인
3. **"← 메인으로 돌아가기"** 버튼 클릭
4. 메인 페이지로 돌아오는지 확인

**예상 결과:** 페이지 간 이동이 정상 작동함

---

## Part 6: 결과 확인 및 Git 제출 (10분)

### 6.1 완료 체크리스트

- [ ] `index.html` 파일 작성 완료
- [ ] `day1.html` 파일 작성 완료
- [ ] 원하는 테마 적용 (선택)
- [ ] `/var/www/html/`에 파일 복사
- [ ] `http://localhost` 접속 시 메인 페이지 표시
- [ ] Day 1 링크 클릭 시 day1.html로 이동
- [ ] "돌아가기" 링크로 index.html 복귀

### 6.2 결과 파일 저장

```bash
cd ~/projects/linux_diary

# 작업 내용 기록
echo "=== Linux 학습 일지 웹사이트 ===" > result.txt
echo "작성일: $(date)" >> result.txt
echo "" >> result.txt

# 파일 목록
echo "=== 작성한 파일 ===" >> result.txt
ls -la >> result.txt
echo "" >> result.txt

# Nginx 상태
echo "=== Nginx 상태 ===" >> result.txt
sudo systemctl status nginx | head -10 >> result.txt

# 결과 확인
cat result.txt
```

### 6.3 Git 제출

```bash
cd ~/projects/linux_diary

# Git 초기화 (처음인 경우)
git init

# 파일 추가
git add index.html day1.html result.txt

# 커밋
git commit -m "Day 7 응용실습: Linux 학습 일지 웹사이트"

# 원격 저장소 연결 및 푸시 (GitHub 저장소 URL로 변경)
# git remote add origin https://github.com/사용자명/저장소명.git
# git push -u origin main
```

---

## 자주 하는 실수

| 실수 | 해결 방법 |
|------|-----------|
| 파일을 /var/www/html에 복사 안 함 | `sudo cp ~/projects/linux_diary/*.html /var/www/html/` |
| nginx 재시작 안 함 | `sudo systemctl restart nginx` |
| 링크 경로 오타 (Day1.html vs day1.html) | 파일명 대소문자 확인 (Linux는 대소문자 구분!) |
| 브라우저에서 "연결 거부" | nginx 실행 확인: `sudo systemctl start nginx` |
| HTML 태그 닫기 누락 | VSCode에서 빨간 밑줄 확인 |

---

## 추가 도전 과제 (선택)

### 도전 1: Day 2 ~ Day 7 페이지 추가

`day1.html`을 복사하여 `day2.html`, `day3.html` 등을 만들고 각 Day에서 배운 내용을 정리해보세요.

```bash
# 파일 복사
cp ~/projects/linux_diary/day1.html ~/projects/linux_diary/day2.html

# VSCode로 내용 수정
code ~/projects/linux_diary/day2.html
```

### 도전 2: 이미지 추가

```html
<!-- index.html에 이미지 추가 -->
<img src="linux-logo.png" alt="Linux Logo" width="100">
```

```bash
# 이미지 파일도 복사
sudo cp ~/projects/linux_diary/*.png /var/www/html/
```

### 도전 3: 본인만의 스타일 만들기

CSS의 색상 코드를 변경하여 나만의 테마를 만들어보세요:
- 배경색: `background-color`
- 글자색: `color`
- 테두리색: `border-color`

---

## 평가 기준

| 항목 | 배점 |
|------|------|
| index.html 작성 및 구조 | 25% |
| day1.html 작성 및 링크 연결 | 25% |
| Nginx 배포 (파일 복사, 재시작) | 25% |
| 웹사이트 정상 동작 확인 | 15% |
| Git 제출 | 10% |

---

## AI 질문 예시

```
Nginx에서 "403 Forbidden" 오류가 발생합니다.
파일 권한 문제인 것 같은데 어떻게 해결하나요?
```

```
HTML 파일에서 링크를 클릭해도 페이지가 안 바뀝니다.
href 속성을 어떻게 확인해야 하나요?
```

```
CSS로 버튼에 마우스를 올리면 색이 바뀌게 하고 싶습니다.
hover 효과는 어떻게 추가하나요?
```

---

## 마무리

축하합니다! 이제 여러분은:

1. **HTML 웹페이지**를 직접 작성할 수 있습니다
2. **Nginx 웹서버**에 파일을 배포할 수 있습니다
3. **여러 페이지를 링크**로 연결할 수 있습니다
4. **나만의 웹사이트**를 운영할 수 있습니다

이 실습에서 배운 내용은 나중에 Apache나 다른 웹서버에서도 똑같이 적용됩니다!
