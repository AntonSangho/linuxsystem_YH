# 셸 실습 예제 모음

Day 2-3에서 배운 명령어를 활용한 실용적인 예제

---

## 1. 로그 파일 분석

### 실습 준비

```bash
mkdir -p ~/log_lab
cat << 'EOF' > ~/log_lab/access.log
192.168.1.100 - - [01/Jan/2024:10:15:32] "GET /index.html" 200
192.168.1.101 - - [01/Jan/2024:10:16:45] "GET /about.html" 200
192.168.1.100 - - [01/Jan/2024:10:17:22] "POST /login" 500
10.0.0.50 - - [01/Jan/2024:11:20:33] "GET /admin" 403
192.168.1.102 - - [01/Jan/2024:11:25:41] "GET /index.html" 200
192.168.1.100 - - [01/Jan/2024:12:30:15] "GET /products" 200
10.0.0.50 - - [01/Jan/2024:12:31:22] "POST /admin/delete" 500
192.168.1.101 - - [01/Jan/2024:14:45:33] "GET /contact" 200
EOF
```

### 예제 1-1: 에러 로그 추출

```bash
# 500 에러 찾기
grep "500" ~/log_lab/access.log

# 에러 발생 IP만 추출
grep "500" ~/log_lab/access.log | cut -d' ' -f1
```

### 예제 1-2: 접속 IP 통계

```bash
# IP별 접속 횟수
cut -d' ' -f1 ~/log_lab/access.log | sort | uniq -c | sort -rn
```

### 실습 과제

1. 403 에러를 발생시킨 IP를 찾으세요
2. 가장 많이 요청된 페이지를 찾으세요

---

## 2. 파일 일괄 처리

### 실습 준비

```bash
mkdir -p ~/file_lab && cd ~/file_lab
touch report_01.txt report_02.txt report_03.txt
touch photo001.jpeg photo002.jpeg
```

### 예제 2-1: 확장자 변경

```bash
# .jpeg를 .jpg로 변경
for f in *.jpeg; do
    mv "$f" "${f%.jpeg}.jpg"
done
```

### 예제 2-2: 파일명에 날짜 추가

```bash
today=$(date +%Y%m%d)
for f in report_*.txt; do
    mv "$f" "${today}_${f}"
done
```

### 실습 과제

1. 모든 `.txt` 파일 목록을 `filelist.txt`에 저장하세요
2. 파일명의 `report`를 `월간보고서`로 바꿔보세요

---

## 3. 시스템 정보 스크립트

### 예제 3-1: 시스템 리포트

```bash
mkdir -p ~/scripts
cat << 'EOF' > ~/scripts/sysinfo.sh
#!/bin/bash
echo "=============================="
echo "    시스템 정보 리포트"
echo "    $(date)"
echo "=============================="
echo ""
echo "[사용자 정보]"
echo "User: $USER"
echo "Home: $HOME"
echo ""
echo "[디스크 사용량]"
df -h | head -3
echo ""
echo "[메모리 사용량]"
free -h
echo "=============================="
EOF
```

### 실행 방법

```bash
bash ~/scripts/sysinfo.sh

# 결과를 파일로 저장
bash ~/scripts/sysinfo.sh > ~/report.txt
```

---

## 유용한 명령어 조합

| 목적 | 명령어 |
|------|--------|
| 파일 개수 세기 | `ls -1 \| wc -l` |
| 특정 문자열 찾기 | `grep "검색어" 파일` |
| 중복 제거 | `sort 파일 \| uniq` |
| 특정 필드 추출 | `cut -d'구분자' -f번호 파일` |
| 상위 N개 | `명령어 \| head -N` |
