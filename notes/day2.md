# Day 2: 파일 시스템과 기본 명령어

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 실습과제 | 디렉토리 구조 탐색 및 파일 관리 |

## 학습 목표

- 리눅스 파일 시스템 계층 구조 이해
- 기본 파일/디렉토리 조작 명령어 숙달
- AI에게 명령어 옵션 질문하는 연습

---

## 시간표

| 시간 | 내용 |
|------|------|
| 1h | 리눅스 디렉토리 구조 (/, /etc, /home, /var 등) |
| 2h | 기본 명령어: ls, cd, pwd, mkdir, rm, cp, mv |
| 1h | 파일 관리: cat, more, less, head, tail, find |
| 1h | 실습과제 2 |

---

## 1. 리눅스 디렉토리 구조 (1시간)

### 리눅스 파일 시스템의 특징

- **모든 것은 파일이다** (Everything is a file)
- 단일 루트(`/`)에서 시작하는 트리 구조
- Windows의 `C:\`, `D:\`와 달리 드라이브 문자 없음

### 주요 디렉토리

| 디렉토리 | 설명 | 예시 |
|----------|------|------|
| `/` | 루트 디렉토리, 모든 디렉토리의 시작점 | - |
| `/home` | 일반 사용자의 홈 디렉토리 | `/home/ubuntu` |
| `/root` | root 사용자의 홈 디렉토리 | - |
| `/etc` | 시스템 설정 파일 | `/etc/passwd`, `/etc/hosts` |
| `/var` | 가변 데이터 (로그, 캐시 등) | `/var/log`, `/var/www` |
| `/tmp` | 임시 파일 (재부팅 시 삭제) | - |
| `/usr` | 사용자 프로그램 및 데이터 | `/usr/bin`, `/usr/lib` |
| `/bin` | 필수 명령어 바이너리 | `ls`, `cp`, `mv` |
| `/sbin` | 시스템 관리용 명령어 | `fdisk`, `ifconfig` |
| `/dev` | 장치 파일 | `/dev/sda`, `/dev/null` |
| `/proc` | 프로세스 및 커널 정보 (가상) | `/proc/cpuinfo` |
| `/mnt`, `/media` | 마운트 포인트 | USB, CD-ROM |

### 경로의 종류

| 구분 | 설명 | 예시 |
|------|------|------|
| 절대 경로 | 루트(`/`)부터 시작하는 전체 경로 | `/home/ubuntu/Documents` |
| 상대 경로 | 현재 위치 기준 경로 | `./Documents`, `../` |

### 특수 디렉토리 기호

| 기호 | 의미 |
|------|------|
| `.` | 현재 디렉토리 |
| `..` | 상위 디렉토리 |
| `~` | 홈 디렉토리 |
| `-` | 이전 디렉토리 (cd에서 사용) |

### 실습: 디렉토리 탐색

```bash
# 현재 위치 확인
pwd

# 루트 디렉토리 내용 보기
ls /

# 주요 디렉토리 탐색
ls /etc
ls /var/log
ls /home
```

---

## 2. 기본 명령어 (2시간)

### pwd - 현재 디렉토리 확인

```bash
pwd
# 출력: /home/ubuntu
```

### cd - 디렉토리 이동

```bash
cd /etc          # 절대 경로로 이동
cd ..            # 상위 디렉토리로 이동
cd ~             # 홈 디렉토리로 이동
cd               # 홈 디렉토리로 이동 (cd ~와 동일)
cd -             # 이전 디렉토리로 이동
```

### ls - 디렉토리 내용 보기

```bash
ls               # 기본 목록
ls -l            # 상세 정보 (권한, 소유자, 크기, 날짜)
ls -a            # 숨김 파일 포함 (. 으로 시작하는 파일)
ls -la           # 상세 + 숨김 파일
ls -lh           # 파일 크기를 읽기 쉽게 (K, M, G)
ls -lt           # 수정 시간순 정렬
ls -lS           # 파일 크기순 정렬
```

#### ls -l 출력 해석

```
-rw-r--r-- 1 ubuntu ubuntu 1234 Jan 30 10:00 file.txt
│          │ │      │      │    │            │
│          │ │      │      │    │            └─ 파일명
│          │ │      │      │    └─ 수정 날짜
│          │ │      │      └─ 파일 크기 (바이트)
│          │ │      └─ 그룹
│          │ └─ 소유자
│          └─ 링크 수
└─ 파일 유형 및 권한
```

### mkdir - 디렉토리 생성

```bash
mkdir mydir                  # 디렉토리 생성
mkdir -p dir1/dir2/dir3      # 중첩 디렉토리 한번에 생성
mkdir dir1 dir2 dir3         # 여러 디렉토리 동시 생성
```

### rm - 파일/디렉토리 삭제

```bash
rm file.txt           # 파일 삭제
rm -i file.txt        # 삭제 전 확인
rm -r mydir           # 디렉토리 삭제 (재귀적)
rm -rf mydir          # 강제 삭제 (주의!)
```

> **경고**: `rm -rf /` 또는 `rm -rf *`는 시스템을 파괴할 수 있음. 항상 경로 확인!

### cp - 파일/디렉토리 복사

```bash
cp file1.txt file2.txt           # 파일 복사
cp file.txt /tmp/                # 다른 위치로 복사
cp -r dir1 dir2                  # 디렉토리 복사
cp -i file.txt /tmp/             # 덮어쓰기 전 확인
```

### mv - 파일/디렉토리 이동 및 이름 변경

```bash
mv file1.txt file2.txt           # 이름 변경
mv file.txt /tmp/                # 파일 이동
mv dir1 dir2                     # 디렉토리 이동/이름변경
mv -i file.txt /tmp/             # 덮어쓰기 전 확인
```

### 실습: 기본 명령어 연습

```bash
# 홈 디렉토리에서 시작
cd ~

# 연습용 디렉토리 생성
mkdir -p practice/test1/test2

# 디렉토리 이동 및 확인
cd practice
pwd
ls -la

# 빈 파일 생성 (touch)
touch file1.txt file2.txt

# 파일 복사
cp file1.txt file3.txt

# 파일 이름 변경
mv file2.txt renamed.txt

# 파일 삭제
rm file3.txt

# 정리
cd ~
rm -r practice
```

---

## 3. 파일 관리 명령어 (1시간)

### cat - 파일 내용 출력

```bash
cat file.txt              # 파일 전체 출력
cat file1.txt file2.txt   # 여러 파일 연결 출력
cat -n file.txt           # 줄 번호 표시
```

### more / less - 페이지 단위 보기

```bash
more file.txt     # 페이지 단위 (앞으로만)
less file.txt     # 페이지 단위 (앞뒤 이동 가능)
```

#### less 단축키

| 키 | 동작 |
|-----|------|
| `Space` | 다음 페이지 |
| `b` | 이전 페이지 |
| `/검색어` | 검색 |
| `n` | 다음 검색 결과 |
| `q` | 종료 |

### head / tail - 파일 앞/뒤 부분 보기

```bash
head file.txt         # 처음 10줄
head -n 20 file.txt   # 처음 20줄
tail file.txt         # 마지막 10줄
tail -n 20 file.txt   # 마지막 20줄
tail -f /var/log/syslog   # 실시간 로그 모니터링
```

### find - 파일 검색

```bash
find /home -name "*.txt"           # 이름으로 검색
find . -type f                     # 파일만 검색
find . -type d                     # 디렉토리만 검색
find . -name "*.log" -size +1M     # 1MB 이상 로그 파일
find /tmp -mtime +7                # 7일 이상 된 파일
```

### 실습: 시스템 로그 확인

```bash
# 시스템 로그 위치 확인
ls /var/log

# 로그 파일 미리보기
head /var/log/syslog
tail /var/log/syslog

# 실시간 로그 모니터링 (Ctrl+C로 종료)
tail -f /var/log/syslog
```

---

## 4. 실습과제 2: 디렉토리 구조 탐색 및 파일 관리 (1시간)

### 과제 목표

- 리눅스 디렉토리 구조 탐색
- 파일/디렉토리 생성, 복사, 이동, 삭제 수행
- 파일 내용 확인 명령어 활용

### 수행 단계

#### Part 1: 디렉토리 탐색

1. 다음 디렉토리들을 탐색하고 내용 확인
   - `/etc` - 설정 파일 확인
   - `/var/log` - 로그 파일 확인
   - `/home` - 사용자 디렉토리 확인

```bash
ls -la /etc
ls -la /var/log
ls -la /home
```

#### Part 2: 디렉토리 및 파일 관리

1. 홈 디렉토리에 다음 구조 생성:

```
~/project/
├── docs/
├── src/
│   └── main/
└── backup/
```

```bash
cd ~
mkdir -p project/docs project/src/main project/backup
```

2. 파일 생성 및 조작

```bash
cd ~/project
touch docs/readme.txt
echo "Hello Linux" > src/main/hello.txt
cp src/main/hello.txt backup/
mv docs/readme.txt docs/README.txt
```

3. 결과 확인

```bash
ls -laR ~/project
cat ~/project/backup/hello.txt
```

#### Part 3: 시스템 파일 탐색

```bash
# /etc/passwd 파일 앞 5줄 확인
head -n 5 /etc/passwd

# /etc 디렉토리에서 .conf 파일 찾기
find /etc -name "*.conf" 2>/dev/null | head -10
```

### 제출 내용

- `ls -laR ~/project` 실행 결과 캡처
- `/etc/passwd` 앞 5줄 캡처
- 수행한 명령어 목록 정리

### 평가 기준

| 항목 | 배점 |
|------|------|
| 디렉토리 구조 생성 | 30% |
| 파일 생성 및 조작 | 40% |
| 시스템 파일 탐색 | 20% |
| 명령어 정리 | 10% |

---

## AI 활용 연습

### 명령어 옵션 물어보기

```
ls 명령어의 주요 옵션들과 각각의 기능을 알려주세요.
특히 -l, -a, -h, -t, -S 옵션이 무엇인지 설명해주세요.
```

```
find 명령어로 특정 크기 이상의 파일을 찾으려면 어떻게 하나요?
예시와 함께 알려주세요.
```

---

## 수업 진행 팁

- [ ] 명령어 실행 전 현재 위치(`pwd`) 확인 습관 강조
- [ ] `rm -rf` 위험성 반복 강조
- [ ] Tab 자동완성 기능 소개
- [ ] 명령어 히스토리 (위/아래 화살표, `history`) 소개

---

## 예상 질문 및 답변

### Q: 삭제한 파일을 복구할 수 있나요?
**A**: 리눅스에는 기본 휴지통이 없음. `rm`으로 삭제하면 복구 어려움. 중요 파일은 삭제 전 백업 필수.

### Q: 숨김 파일은 왜 `.`으로 시작하나요?
**A**: 유닉스 전통. `.bashrc`, `.profile` 등 설정 파일들이 일반 파일과 섞이지 않도록 숨김 처리.

### Q: `rm -rf /`를 실행하면 어떻게 되나요?
**A**: 시스템 전체 삭제. 현대 리눅스는 `--no-preserve-root` 없이는 막아줌. 하지만 절대 시도 금지!

---

## 다음 수업 예고

**Day 3: 셸 기초**
- Bash 셸: 환경변수, alias, 히스토리
- 리다이렉션, 파이프, 명령어 조합
