# Day 4: 권한 관리와 프로세스

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 실습과제 | 사용자별 권한 설정 및 백그라운드 프로세스 관리 |

## 학습 목표

- 리눅스 권한 체계(rwx) 완벽 이해
- 프로세스 모니터링 및 제어 능력 습득

---

## 시간표

| 시간 | 내용 |
|------|------|
| 2h | 파일 권한: chmod, chown, chgrp, umask |
| 2h | 프로세스 관리: ps, top, kill, jobs, bg, fg, nohup |
| 1h | 실습과제 4 |

---

## 1. 파일 권한 (2시간)

### 권한의 개념

리눅스는 다중 사용자 시스템으로, 파일과 디렉토리에 대한 접근을 제어함

```bash
ls -l file.txt
# -rw-r--r-- 1 ubuntu ubuntu 1234 Jan 30 10:00 file.txt
```

### 권한 구조 해석

```
-rw-r--r--
│└┬┘└┬┘└┬┘
│ │  │  └── 기타 사용자(Others) 권한
│ │  └───── 그룹(Group) 권한
│ └──────── 소유자(Owner) 권한
└────────── 파일 유형
```

### 파일 유형

| 기호 | 유형 |
|------|------|
| `-` | 일반 파일 |
| `d` | 디렉토리 |
| `l` | 심볼릭 링크 |
| `b` | 블록 장치 |
| `c` | 문자 장치 |

### 권한 종류

| 권한 | 기호 | 숫자 | 파일에서 | 디렉토리에서 |
|------|------|------|----------|--------------|
| 읽기 | r | 4 | 내용 읽기 | 목록 보기 (ls) |
| 쓰기 | w | 2 | 내용 수정 | 파일 생성/삭제 |
| 실행 | x | 1 | 실행 | 디렉토리 진입 (cd) |

### 권한의 숫자 표현

```
rwx = 4 + 2 + 1 = 7
rw- = 4 + 2 + 0 = 6
r-x = 4 + 0 + 1 = 5
r-- = 4 + 0 + 0 = 4
--- = 0 + 0 + 0 = 0
```

#### 자주 사용하는 권한

| 숫자 | 기호 | 설명 | 용도 |
|------|------|------|------|
| 755 | rwxr-xr-x | 소유자 모든 권한, 그룹/기타 읽기+실행 | 실행 파일, 디렉토리 |
| 644 | rw-r--r-- | 소유자 읽기+쓰기, 그룹/기타 읽기 | 일반 파일 |
| 700 | rwx------ | 소유자만 모든 권한 | 개인 스크립트 |
| 600 | rw------- | 소유자만 읽기+쓰기 | 비밀 파일 (SSH 키) |
| 777 | rwxrwxrwx | 모든 권한 (위험!) | 거의 사용 안 함 |

### chmod - 권한 변경

#### 숫자 모드

```bash
chmod 755 script.sh       # rwxr-xr-x
chmod 644 file.txt        # rw-r--r--
chmod 600 private.key     # rw-------
chmod 777 public/         # rwxrwxrwx (주의!)
```

#### 기호 모드

```bash
# 구문: chmod [대상][연산자][권한] 파일

# 대상: u(user), g(group), o(others), a(all)
# 연산자: +(추가), -(제거), =(설정)
# 권한: r, w, x

chmod u+x script.sh       # 소유자에게 실행 권한 추가
chmod g-w file.txt        # 그룹에서 쓰기 권한 제거
chmod o=r file.txt        # 기타 사용자 읽기만 허용
chmod a+r file.txt        # 모두에게 읽기 권한 추가
chmod u+x,g+r script.sh   # 여러 권한 동시 변경
```

#### 재귀적 권한 변경

```bash
chmod -R 755 mydir/       # 디렉토리와 모든 하위 파일/폴더
```

### chown - 소유자 변경

```bash
# 소유자 변경
sudo chown newuser file.txt

# 소유자와 그룹 동시 변경
sudo chown newuser:newgroup file.txt

# 그룹만 변경
sudo chown :newgroup file.txt

# 재귀적 변경
sudo chown -R newuser:newgroup mydir/
```

### chgrp - 그룹 변경

```bash
sudo chgrp developers file.txt
sudo chgrp -R developers mydir/
```

### umask - 기본 권한 설정

- 새로 생성되는 파일/디렉토리의 기본 권한 결정
- 기본 권한에서 umask 값을 뺌

```bash
# 현재 umask 확인
umask

# umask 설정
umask 022    # 일반적인 설정

# 계산 방법
# 파일 기본: 666 - umask = 실제 권한
# 디렉토리 기본: 777 - umask = 실제 권한

# umask 022인 경우:
# 파일: 666 - 022 = 644 (rw-r--r--)
# 디렉토리: 777 - 022 = 755 (rwxr-xr-x)
```

| umask | 파일 권한 | 디렉토리 권한 |
|-------|-----------|---------------|
| 022 | 644 | 755 |
| 027 | 640 | 750 |
| 077 | 600 | 700 |

### 특수 권한

| 권한 | 숫자 | 설명 |
|------|------|------|
| SetUID | 4000 | 실행 시 소유자 권한으로 실행 |
| SetGID | 2000 | 실행 시 그룹 권한으로 실행 |
| Sticky Bit | 1000 | 디렉토리에서 소유자만 삭제 가능 |

```bash
# SetUID 예시 (passwd 명령어)
ls -l /usr/bin/passwd
# -rwsr-xr-x (s = SetUID)

# Sticky Bit 예시 (/tmp 디렉토리)
ls -ld /tmp
# drwxrwxrwt (t = Sticky Bit)
```

---

## 2. 프로세스 관리 (2시간)

### 프로세스란?

- 실행 중인 프로그램의 인스턴스
- 각 프로세스는 고유한 PID(Process ID)를 가짐
- 부모-자식 관계로 구성 (init/systemd가 최상위)

### ps - 프로세스 확인

```bash
# 현재 터미널의 프로세스
ps

# 모든 프로세스 (상세)
ps aux

# 모든 프로세스 (트리 형태)
ps -ef

# 특정 사용자 프로세스
ps -u ubuntu

# 특정 프로세스 검색
ps aux | grep apache
```

#### ps aux 출력 해석

```
USER       PID %CPU %MEM    VSZ   RSS TTY      STAT START   TIME COMMAND
root         1  0.0  0.1 168936 11420 ?        Ss   10:00   0:01 /sbin/init
ubuntu    1234  0.5  1.2 123456 12345 pts/0    S    10:30   0:05 /usr/bin/python3
```

| 필드 | 설명 |
|------|------|
| USER | 프로세스 소유자 |
| PID | 프로세스 ID |
| %CPU | CPU 사용률 |
| %MEM | 메모리 사용률 |
| VSZ | 가상 메모리 크기 |
| RSS | 실제 메모리 사용량 |
| TTY | 터미널 |
| STAT | 프로세스 상태 |
| START | 시작 시간 |
| TIME | CPU 사용 시간 |
| COMMAND | 실행 명령어 |

#### 프로세스 상태 (STAT)

| 상태 | 설명 |
|------|------|
| R | Running (실행 중) |
| S | Sleeping (대기 중) |
| D | Disk sleep (I/O 대기) |
| T | Stopped (중지됨) |
| Z | Zombie (좀비 프로세스) |

### top - 실시간 모니터링

```bash
top
```

#### top 화면 구성

```
top - 10:30:00 up 1 day,  2:30,  1 user,  load average: 0.00, 0.01, 0.05
Tasks: 120 total,   1 running, 119 sleeping,   0 stopped,   0 zombie
%Cpu(s):  0.3 us,  0.1 sy,  0.0 ni, 99.5 id,  0.0 wa,  0.0 hi,  0.0 si
MiB Mem :   3936.5 total,   2500.0 free,    800.0 used,    636.5 buff/cache
MiB Swap:   2048.0 total,   2048.0 free,      0.0 used.   2900.0 avail Mem
```

#### top 단축키

| 키 | 동작 |
|----|------|
| `q` | 종료 |
| `h` | 도움말 |
| `k` | 프로세스 종료 (kill) |
| `M` | 메모리 사용량 정렬 |
| `P` | CPU 사용량 정렬 |
| `u` | 특정 사용자 필터 |
| `1` | CPU 코어별 표시 |

### htop - 향상된 모니터링

```bash
# 설치
sudo apt install htop

# 실행
htop
```

### kill - 프로세스 종료

```bash
# 기본 종료 (SIGTERM, 15)
kill 1234

# 강제 종료 (SIGKILL, 9)
kill -9 1234

# 프로세스 이름으로 종료
killall firefox
pkill -f "python script.py"
```

#### 주요 시그널

| 시그널 | 번호 | 설명 |
|--------|------|------|
| SIGHUP | 1 | 재시작 |
| SIGINT | 2 | 인터럽트 (Ctrl+C) |
| SIGKILL | 9 | 강제 종료 (무시 불가) |
| SIGTERM | 15 | 정상 종료 (기본값) |
| SIGSTOP | 19 | 일시 정지 |
| SIGCONT | 18 | 재개 |

### 포그라운드 / 백그라운드 프로세스

```bash
# 포그라운드 실행 (기본)
./long_script.sh

# 백그라운드 실행
./long_script.sh &

# 실행 중인 작업 목록
jobs

# 포그라운드 → 백그라운드
# 1. Ctrl+Z (일시 정지)
# 2. bg (백그라운드로 전환)

# 백그라운드 → 포그라운드
fg %1        # 작업 번호 1을 포그라운드로

# 백그라운드 작업 재개
bg %1
```

### nohup - 로그아웃 후에도 실행

```bash
# 기본 사용
nohup ./long_script.sh &

# 출력 파일 지정
nohup ./long_script.sh > output.log 2>&1 &

# 프로세스 ID 확인
echo $!
```

### 프로세스 우선순위 (nice)

```bash
# nice 값 확인 (-20 ~ 19, 낮을수록 높은 우선순위)
ps -l

# 낮은 우선순위로 실행
nice -n 10 ./heavy_script.sh

# 실행 중인 프로세스 우선순위 변경
renice 10 -p 1234
sudo renice -10 -p 1234    # 높은 우선순위는 root 필요
```

---

## 3. 실습과제 4: 권한 설정 및 프로세스 관리 (1시간)

### 과제 목표

- 파일/디렉토리 권한 설정 실습
- 프로세스 모니터링 및 제어 실습
- 백그라운드 프로세스 관리

### 수행 단계

#### Part 1: 권한 설정

1. 실습 디렉토리 생성

```bash
cd ~
mkdir -p permission_test
cd permission_test
```

2. 다양한 권한의 파일 생성

```bash
# 파일 생성
touch public.txt private.txt script.sh

# 권한 설정
chmod 644 public.txt      # 일반 파일
chmod 600 private.txt     # 비밀 파일
chmod 755 script.sh       # 실행 파일

# 권한 확인
ls -l
```

3. 스크립트 파일 생성 및 실행

```bash
# 스크립트 내용 작성
cat << 'EOF' > script.sh
#!/bin/bash
echo "Hello from script!"
echo "Current user: $USER"
echo "Current time: $(date)"
EOF

# 실행 테스트
./script.sh
```

4. 디렉토리 권한 실습

```bash
mkdir shared_dir secret_dir

chmod 755 shared_dir      # 모든 사용자 접근 가능
chmod 700 secret_dir      # 소유자만 접근 가능

ls -ld shared_dir secret_dir
```

#### Part 2: 프로세스 모니터링

1. 시스템 프로세스 확인

```bash
# 모든 프로세스 확인
ps aux | head -20

# CPU 사용량 상위 5개
ps aux --sort=-%cpu | head -6

# 메모리 사용량 상위 5개
ps aux --sort=-%mem | head -6
```

2. top으로 모니터링

```bash
# top 실행 (q로 종료)
top

# 5초 동안만 실행
top -b -n 1 | head -20
```

#### Part 3: 백그라운드 프로세스 관리

1. 테스트 스크립트 생성

```bash
cat << 'EOF' > ~/permission_test/long_task.sh
#!/bin/bash
echo "Task started at $(date)"
for i in {1..10}; do
    echo "Processing... $i/10"
    sleep 2
done
echo "Task completed at $(date)"
EOF

chmod +x ~/permission_test/long_task.sh
```

2. 백그라운드 실행

```bash
# 백그라운드로 실행
./long_task.sh > task_output.log 2>&1 &

# 프로세스 ID 저장
echo "PID: $!"

# 작업 목록 확인
jobs

# 로그 실시간 확인
tail -f task_output.log
# Ctrl+C로 종료
```

3. nohup으로 실행

```bash
nohup ./long_task.sh > nohup_output.log 2>&1 &
echo "Nohup PID: $!"

# 결과 확인
cat nohup_output.log
```

4. 프로세스 종료 실습

```bash
# sleep 프로세스 시작
sleep 300 &
echo "Sleep PID: $!"

# 프로세스 확인
ps aux | grep sleep

# 프로세스 종료
kill $!

# 종료 확인
ps aux | grep sleep
```

### 제출 내용

- `ls -l ~/permission_test/` 결과 캡처
- `ps aux --sort=-%mem | head -10` 결과 캡처
- 백그라운드 프로세스 실행 및 `jobs` 결과 캡처
- `task_output.log` 내용 캡처

### 평가 기준

| 항목 | 배점 |
|------|------|
| 파일 권한 설정 | 30% |
| 프로세스 모니터링 | 30% |
| 백그라운드 프로세스 관리 | 30% |
| 결과 정리 | 10% |

---

## 수업 진행 팁

- [ ] 권한 숫자(755, 644) 계산 방법 반복 연습
- [ ] `chmod 777` 사용의 위험성 강조
- [ ] `kill -9`는 최후의 수단임을 강조
- [ ] 실제 서버 관리 사례와 연결 (AWS EC2 권한 관리)

---

## 예상 질문 및 답변

### Q: 왜 777 권한은 위험한가요?
**A**: 모든 사용자가 읽기/쓰기/실행 가능. 악성 사용자가 파일 수정 가능. 특히 웹 서버 파일에 777 사용 시 보안 취약점 발생.

### Q: 실행 권한 없이 스크립트를 실행할 수 있나요?
**A**: `bash script.sh`처럼 명시적으로 인터프리터 호출 시 가능. 하지만 `./script.sh`로 직접 실행하려면 x 권한 필요.

### Q: 좀비 프로세스란 무엇인가요?
**A**: 실행은 끝났지만 부모 프로세스가 종료 상태를 수집하지 않은 프로세스. 리소스는 거의 사용 안 함. 부모 프로세스 종료 시 자동 정리됨.

### Q: Ctrl+C와 kill의 차이는?
**A**: Ctrl+C는 SIGINT(2) 전송, kill은 기본 SIGTERM(15) 전송. 둘 다 프로그램이 무시 가능. kill -9(SIGKILL)만 강제 종료.

---

## 다음 수업 예고

**Day 5: 시스템 관리 기초**
- 디스크 관리: df, du, fdisk, mount/umount
- 부팅 프로세스와 systemd
- 패키지 관리: apt, dpkg
