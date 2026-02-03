# Day 2~4 종합 실습 과제: 나만의 시스템 정보 스크립트 만들기

## 과제 개요

| 항목 | 내용 |
|------|------|
| 목표 | Day 2~4에서 배운 명령어로 간단한 bash 스크립트 작성 |
| 최종 결과물 | `my_system.sh` 스크립트 파일 |
| 실습 환경 | Ubuntu 22.04 (VMware) |
| 난이도 | ★★☆☆☆ (초급) |

---

## Part 1: 실습 준비 (10분)

### 1-1. 작업 디렉토리 만들기

```bash
# 홈 디렉토리로 이동
cd ~

# 실습 폴더 생성
mkdir linux_lab

# 폴더로 이동
cd linux_lab

# 현재 위치 확인
pwd
```

### 1-2. 테스트 파일 만들기

```bash
# 간단한 로그 파일 생성
echo "INFO: 시스템 시작" > test.log
echo "ERROR: 연결 실패" >> test.log
echo "INFO: 작업 완료" >> test.log
echo "ERROR: 파일 없음" >> test.log

# 파일 확인
cat test.log
```

---

## Part 2: 명령어 연습 (15분)

스크립트를 만들기 전에 사용할 명령어들을 먼저 연습합니다.

### 2-1. 시스템 정보 명령어

```bash
# 오늘 날짜
date

# 현재 사용자
echo $USER

# 컴퓨터 이름
hostname

# 디스크 사용량
df -h
```

### 2-2. 파이프 연습

```bash
# 로그에서 ERROR만 찾기
cat test.log | grep ERROR

# ERROR 개수 세기
cat test.log | grep ERROR | wc -l

# 디스크 정보 앞 5줄만 보기
df -h | head -5
```

### 2-3. 리다이렉션 연습

```bash
# 결과를 파일로 저장
date > result.txt

# 결과를 파일에 추가
echo "사용자: $USER" >> result.txt

# 파일 내용 확인
cat result.txt
```

---

## Part 3: 스크립트 작성 (핵심 과제, 20분)

### 3-1. 스크립트 파일 생성

```bash
# 스크립트 파일 만들기
cat << 'EOF' > my_system.sh
#!/bin/bash
# 나의 첫 번째 시스템 정보 스크립트
# 작성자: [본인 이름]

echo "==============================="
echo "    시스템 정보 리포트"
echo "==============================="
echo ""

# 1. 날짜 출력
echo "[현재 날짜]"
date
echo ""

# 2. 사용자 정보
echo "[사용자 정보]"
echo "사용자: $USER"
echo "홈 디렉토리: $HOME"
echo ""

# 3. 디스크 사용량 (상위 5줄)
echo "[디스크 사용량]"
df -h | head -5
echo ""

# 4. 에러 로그 확인
echo "[에러 로그]"
echo "에러 개수:"
cat test.log | grep ERROR | wc -l
echo ""
echo "에러 내용:"
cat test.log | grep ERROR
echo ""

echo "==============================="
echo "    리포트 완료!"
echo "==============================="
EOF
```

### 3-2. 스크립트 내용 확인

```bash
cat my_system.sh
```

---

## Part 4: 권한 설정 및 실행 (10분)

### 4-1. 현재 권한 확인

```bash
ls -l my_system.sh
```

**예상 결과:** `-rw-r--r--` (실행 권한 없음)

### 4-2. 실행 권한 부여

```bash
# 숫자 방식으로 권한 변경
chmod 755 my_system.sh

# 권한 확인
ls -l my_system.sh
```

**예상 결과:** `-rwxr-xr-x` (실행 권한 있음)

### 4-3. 스크립트 실행

```bash
./my_system.sh
```

---

## Part 5: 결과를 파일로 저장 (5분)

```bash
# 실행 결과를 파일로 저장
./my_system.sh > report.txt

# 저장된 파일 확인
cat report.txt
```

---

## 제출 내용

다음 3가지를 캡처하여 제출하세요:

1. `ls -l my_system.sh` 결과 (권한 확인)
2. `./my_system.sh` 실행 결과
3. `cat my_system.sh` 스크립트 내용

---

## 평가 기준

| 항목 | 배점 | 확인 사항 |
|------|------|----------|
| 디렉토리 생성 | 20% | `mkdir`, `cd` 사용 |
| 스크립트 작성 | 40% | 파일 생성, 내용 정확성 |
| 권한 설정 | 20% | `chmod 755` 적용 |
| 실행 성공 | 20% | 오류 없이 실행 |

---

## 자주 하는 실수

### 실수 1: "Permission denied" 오류
```bash
# 원인: 실행 권한이 없음
# 해결:
chmod 755 my_system.sh
```

### 실수 2: "No such file or directory" 오류
```bash
# 원인: test.log 파일이 없음
# 해결: test.log 파일 먼저 생성
echo "ERROR: 테스트" > test.log
```

### 실수 3: 스크립트가 실행 안 됨
```bash
# 해결 1: 경로 지정
./my_system.sh

# 해결 2: bash로 직접 실행
bash my_system.sh
```

---

## 도전 과제 (선택)

시간이 남으면 스크립트에 다음 내용을 추가해보세요:

### 도전 1: 메모리 정보 추가

스크립트에 다음 내용 추가:
```bash
echo "[메모리 사용량]"
free -h
echo ""
```

### 도전 2: 현재 폴더 파일 목록 추가

```bash
echo "[현재 폴더 파일]"
ls -la
echo ""
```

---

## 완료 확인 체크리스트

- [ ] `~/linux_lab` 폴더가 생성되었다
- [ ] `test.log` 파일이 있다
- [ ] `my_system.sh` 파일이 있다
- [ ] `ls -l my_system.sh` 결과가 `-rwxr-xr-x`이다
- [ ] `./my_system.sh` 실행 시 오류가 없다
