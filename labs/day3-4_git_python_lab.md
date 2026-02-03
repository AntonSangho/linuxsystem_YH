# Day 3-4 실습: Git 설치 및 Python 환경 설정

## 실습 개요

| 항목 | 내용 |
|------|------|
| 목표 | Git 설치, Python 환경변수 설정, SSH 원격 실습 |
| 연관 수업 | Day 3 (셸 기초, 환경변수), Day 4 (권한, 프로세스) |
| 환경 | Ubuntu (VM 또는 라즈베리파이) |
| 소요 시간 | 약 2시간 |

---

## Part 1: Git 설치 및 기본 설정 (30분)

### 1.1 Git 설치

```bash
# 패키지 목록 업데이트
sudo apt update

# Git 설치
sudo apt install git -y

# 설치 확인
git --version
```

### 1.2 Git 초기 설정

```bash
# 사용자 정보 설정 (본인 이름/이메일로 변경)
git config --global user.name "홍길동"
git config --global user.email "hong@example.com"

# 기본 편집기 설정
git config --global core.editor "nano"

# 설정 확인
git config --list
```

### 1.3 Git 저장소 만들기 실습

```bash
# 실습 디렉토리 생성
mkdir -p ~/git_practice
cd ~/git_practice

# Git 저장소 초기화
git init

# 상태 확인
git status

# 파일 생성
echo "# My First Git Project" > README.md
echo "print('Hello, Git!')" > hello.py

# 파일 추가 및 커밋
git add .
git status
git commit -m "Initial commit: Add README and hello.py"

# 커밋 로그 확인
git log --oneline
```

### 1.4 Git 명령어 alias 설정

```bash
# ~/.bashrc에 Git alias 추가
cat << 'EOF' >> ~/.bashrc

# Git aliases
alias gs='git status'
alias ga='git add'
alias gc='git commit'
alias gl='git log --oneline'
alias gd='git diff'
EOF

# 적용
source ~/.bashrc

# 테스트
gs
```

---

## Part 2: Python 환경 변수 설정 (40분)

### 2.1 Python 설치 확인

```bash
# Python 버전 확인
python3 --version

# Python 위치 확인
which python3

# pip 확인
pip3 --version

# pip 없으면 설치
sudo apt install python3-pip -y
```

### 2.2 Python 프로젝트 구조 만들기

```bash
# 프로젝트 디렉토리 생성
mkdir -p ~/python_project/{src,scripts,config}
cd ~/python_project

# 디렉토리 구조 확인
tree . 2>/dev/null || ls -R
```

### 2.3 Python 스크립트 작성

```bash
# 메인 스크립트 생성
cat << 'EOF' > ~/python_project/scripts/system_info.py
#!/usr/bin/env python3
"""시스템 정보를 출력하는 스크립트"""

import os
import platform
import sys

def main():
    print("=" * 50)
    print("        System Information")
    print("=" * 50)

    # 환경 변수 출력
    print("\n[Environment Variables]")
    print(f"USER: {os.environ.get('USER', 'N/A')}")
    print(f"HOME: {os.environ.get('HOME', 'N/A')}")
    print(f"PATH: {os.environ.get('PATH', 'N/A')[:50]}...")
    print(f"MY_PROJECT: {os.environ.get('MY_PROJECT', 'Not Set')}")
    print(f"PYTHONPATH: {os.environ.get('PYTHONPATH', 'Not Set')}")

    # 시스템 정보
    print("\n[System Info]")
    print(f"OS: {platform.system()} {platform.release()}")
    print(f"Python: {sys.version}")
    print(f"Architecture: {platform.machine()}")

    # 라즈베리파이 확인
    if os.path.exists('/proc/device-tree/model'):
        with open('/proc/device-tree/model', 'r') as f:
            print(f"Device: {f.read().strip()}")

    print("\n" + "=" * 50)

if __name__ == "__main__":
    main()
EOF

# 실행 권한 부여
chmod +x ~/python_project/scripts/system_info.py
```

### 2.4 Python 환경 변수 설정

```bash
# 임시 환경 변수 설정 (현재 세션만)
export MY_PROJECT=~/python_project
export PYTHONPATH=$MY_PROJECT/src:$PYTHONPATH

# 확인
echo $MY_PROJECT
echo $PYTHONPATH

# 스크립트 실행
python3 ~/python_project/scripts/system_info.py
```

### 2.5 영구 환경 변수 설정

```bash
# ~/.bashrc에 Python 환경 변수 추가
cat << 'EOF' >> ~/.bashrc

# Python Project Environment
export MY_PROJECT=~/python_project
export PYTHONPATH=$MY_PROJECT/src:$PYTHONPATH

# Python scripts를 PATH에 추가
export PATH=$PATH:$MY_PROJECT/scripts
EOF

# 적용
source ~/.bashrc

# 확인
echo "MY_PROJECT: $MY_PROJECT"
echo "PYTHONPATH: $PYTHONPATH"
echo "PATH includes scripts: $(echo $PATH | grep -o 'python_project/scripts')"

# 이제 어디서든 실행 가능
cd ~
system_info.py
```

### 2.6 Python 가상 환경 (Virtual Environment)

```bash
# venv 패키지 설치 (없는 경우)
sudo apt install python3-venv -y

# 가상 환경 생성
cd ~/python_project
python3 -m venv venv

# 가상 환경 활성화
source venv/bin/activate

# 가상 환경 확인 (프롬프트에 (venv) 표시)
which python
which pip

# 패키지 설치 예시
pip install requests

# 설치된 패키지 확인
pip list

# 가상 환경 비활성화
deactivate
```

---

## Part 3: 라즈베리파이 SSH 실습 (50분)

> **전제조건**: 라즈베리파이가 네트워크에 연결되어 있고 SSH가 활성화되어 있어야 합니다.

### 3.1 라즈베리파이 SSH 접속

```bash
# Windows/Linux/Mac에서 라즈베리파이로 접속
ssh pi@<라즈베리파이_IP>
# 예: ssh pi@192.168.1.100

# 기본 비밀번호: raspberry (첫 접속 후 변경 권장)
```

### 3.2 라즈베리파이 기본 정보 확인

```bash
# 호스트명 확인
hostname

# 라즈베리파이 모델 확인
cat /proc/device-tree/model

# OS 버전 확인
cat /etc/os-release

# CPU 정보
cat /proc/cpuinfo | head -20

# 메모리 정보
free -h

# 디스크 사용량
df -h

# IP 주소 확인
hostname -I
ip addr
```

### 3.3 시스템 모니터링 스크립트 작성

```bash
# 모니터링 스크립트 생성
mkdir -p ~/scripts
cat << 'EOF' > ~/scripts/rpi_monitor.sh
#!/bin/bash
# 라즈베리파이 모니터링 스크립트

echo "================================"
echo "  Raspberry Pi Monitor"
echo "  $(date '+%Y-%m-%d %H:%M:%S')"
echo "================================"
echo ""

# CPU 온도
if [ -f /sys/class/thermal/thermal_zone0/temp ]; then
    TEMP=$(cat /sys/class/thermal/thermal_zone0/temp)
    TEMP_C=$((TEMP/1000))
    echo "[CPU Temperature]"
    echo "  $TEMP_C°C"
    echo ""
fi

# CPU 사용률
echo "[CPU Usage]"
top -bn1 | grep "Cpu(s)" | awk '{print "  " $2 "% user, " $4 "% system"}'
echo ""

# 메모리 사용량
echo "[Memory Usage]"
free -h | awk 'NR==2{printf "  Used: %s / Total: %s (%.1f%%)\n", $3, $2, $3*100/$2}'
echo ""

# 디스크 사용량
echo "[Disk Usage]"
df -h / | awk 'NR==2{printf "  Used: %s / Total: %s (%s)\n", $3, $2, $5}'
echo ""

# 실행 중인 프로세스 수
echo "[Processes]"
echo "  Running: $(ps aux | wc -l)"
echo ""

# 네트워크 연결
echo "[Network]"
echo "  IP: $(hostname -I | awk '{print $1}')"
echo ""

echo "================================"
EOF

# 실행 권한 부여
chmod +x ~/scripts/rpi_monitor.sh

# 실행
~/scripts/rpi_monitor.sh
```

### 3.4 Python으로 GPIO 제어 (라즈베리파이 전용)

```bash
# GPIO 라이브러리 설치
sudo apt install python3-rpi.gpio -y

# LED 제어 스크립트 (GPIO 연결 시)
cat << 'EOF' > ~/scripts/led_control.py
#!/usr/bin/env python3
"""라즈베리파이 LED 제어 예제 (GPIO 17번 핀)"""

import sys
try:
    import RPi.GPIO as GPIO
    import time
except ImportError:
    print("This script only works on Raspberry Pi")
    sys.exit(1)

LED_PIN = 17

def setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(LED_PIN, GPIO.OUT)

def blink(times=5, interval=0.5):
    """LED 깜빡이기"""
    print(f"Blinking LED {times} times...")
    for i in range(times):
        GPIO.output(LED_PIN, GPIO.HIGH)
        print(f"  LED ON ({i+1}/{times})")
        time.sleep(interval)
        GPIO.output(LED_PIN, GPIO.LOW)
        print(f"  LED OFF")
        time.sleep(interval)

def cleanup():
    GPIO.cleanup()

if __name__ == "__main__":
    try:
        setup()
        blink()
    finally:
        cleanup()
        print("Done!")
EOF

chmod +x ~/scripts/led_control.py
```

### 3.5 백그라운드 서비스 실습

```bash
# 백그라운드에서 모니터링 로그 기록
cat << 'EOF' > ~/scripts/background_logger.sh
#!/bin/bash
# 백그라운드 로깅 스크립트

LOG_FILE=~/monitor_log.txt

while true; do
    echo "$(date '+%Y-%m-%d %H:%M:%S') - CPU: $(top -bn1 | grep 'Cpu(s)' | awk '{print $2}')% | MEM: $(free | awk 'NR==2{printf "%.1f%%", $3*100/$2}')" >> $LOG_FILE
    sleep 60
done
EOF

chmod +x ~/scripts/background_logger.sh

# nohup으로 백그라운드 실행
nohup ~/scripts/background_logger.sh &
echo "Logger PID: $!"

# 실행 확인
jobs
ps aux | grep background_logger

# 로그 확인
tail -f ~/monitor_log.txt
# Ctrl+C로 tail 종료

# 나중에 프로세스 종료할 때
# pkill -f background_logger.sh
```

### 3.6 SSH 세션 유지하며 작업하기 (screen/tmux)

```bash
# screen 설치
sudo apt install screen -y

# 새 screen 세션 시작
screen -S mywork

# screen 내에서 작업 (예: 모니터링)
htop

# screen 분리 (Ctrl+A, D)
# 이후 SSH 연결을 끊어도 작업 유지

# 나중에 재접속 후 세션 복귀
screen -r mywork

# screen 종료
exit
```

---

## Part 4: 종합 실습 과제

### 과제 1: 환경 설정 스크립트 만들기

```bash
# 새 시스템 설정 자동화 스크립트
cat << 'EOF' > ~/scripts/setup_dev_env.sh
#!/bin/bash
# 개발 환경 자동 설정 스크립트

echo "=== Development Environment Setup ==="
echo ""

# Git 설정
echo "[1/4] Configuring Git..."
read -p "Enter your name: " GIT_NAME
read -p "Enter your email: " GIT_EMAIL
git config --global user.name "$GIT_NAME"
git config --global user.email "$GIT_EMAIL"
echo "Git configured!"
echo ""

# 환경 변수 설정
echo "[2/4] Setting environment variables..."
cat << 'BASHRC' >> ~/.bashrc

# Custom Development Environment
export DEV_HOME=~/development
export SCRIPTS=$DEV_HOME/scripts
export PATH=$PATH:$SCRIPTS

# Aliases
alias ll='ls -la'
alias gs='git status'
alias ga='git add'
alias gc='git commit'
BASHRC
echo "Environment variables added to ~/.bashrc"
echo ""

# 디렉토리 생성
echo "[3/4] Creating directories..."
mkdir -p ~/development/{projects,scripts,config}
echo "Directories created!"
echo ""

# Python 가상 환경
echo "[4/4] Setting up Python virtual environment..."
cd ~/development
python3 -m venv venv
echo "Virtual environment created!"
echo ""

echo "=== Setup Complete! ==="
echo "Run 'source ~/.bashrc' to apply changes"
EOF

chmod +x ~/scripts/setup_dev_env.sh
```

### 과제 2: 시스템 상태 리포트 생성

다음 정보를 포함하는 리포트 스크립트를 작성하세요:

1. Git 설정 정보
2. Python 환경 정보 (버전, PYTHONPATH)
3. 시스템 리소스 (CPU, 메모리, 디스크)
4. 실행 중인 Python 프로세스 목록

```bash
# 제출용 스크립트 템플릿
cat << 'EOF' > ~/scripts/full_report.sh
#!/bin/bash
# 종합 시스템 리포트

echo "=== Full System Report ==="
echo "Date: $(date)"
echo ""

echo "[Git Configuration]"
git config --list | grep -E "user\.(name|email)"
echo ""

echo "[Python Environment]"
echo "Python: $(python3 --version)"
echo "PYTHONPATH: $PYTHONPATH"
echo ""

echo "[System Resources]"
echo "CPU Usage:"
top -bn1 | grep "Cpu(s)" | awk '{print "  " $0}'
echo "Memory:"
free -h | awk 'NR<=2{print "  " $0}'
echo "Disk:"
df -h / | awk 'NR<=2{print "  " $0}'
echo ""

echo "[Python Processes]"
ps aux | grep python | grep -v grep | awk '{print "  " $0}'
echo ""

echo "=== Report Complete ==="
EOF

chmod +x ~/scripts/full_report.sh
./scripts/full_report.sh > ~/my_report.txt
cat ~/my_report.txt
```

---

## 제출 내용

| 항목 | 파일/캡처 |
|------|----------|
| 1 | `git config --list` 결과 캡처 |
| 2 | `echo $PYTHONPATH` 및 `echo $PATH` 결과 |
| 3 | `system_info.py` 실행 결과 |
| 4 | `rpi_monitor.sh` 실행 결과 (라즈베리파이) |
| 5 | `full_report.sh` 실행 결과 (`my_report.txt`) |

---

## 평가 기준

| 항목 | 배점 | 세부 내용 |
|------|------|----------|
| Git 설치 및 설정 | 20% | 설치 완료, 사용자 설정 |
| Python 환경 변수 | 30% | PATH, PYTHONPATH 설정, 영구 적용 |
| 스크립트 작성 | 30% | 실행 권한, 정상 동작 |
| SSH 원격 실습 | 20% | 접속 및 모니터링 |

---

## 문제 해결 가이드

### Git 설치 오류

```bash
# 패키지 목록 갱신 후 재시도
sudo apt update
sudo apt install git -y

# 여전히 안되면
sudo apt --fix-broken install
```

### Python 명령어를 찾을 수 없음

```bash
# python3 확인
which python3

# python 심볼릭 링크 생성 (선택)
sudo ln -s /usr/bin/python3 /usr/bin/python
```

### 환경 변수가 적용 안됨

```bash
# source 다시 실행
source ~/.bashrc

# 또는 새 터미널 열기

# ~/.bashrc 내용 확인
cat ~/.bashrc | tail -20
```

### SSH 접속 거부

```bash
# 라즈베리파이에서 SSH 활성화
sudo systemctl enable ssh
sudo systemctl start ssh

# 방화벽 확인
sudo ufw status
sudo ufw allow ssh
```

---

## AI 질문 예시

```
Ubuntu에서 pip install 시 "Permission denied" 오류가 발생합니다.
어떻게 해결해야 하나요?
```

```
PYTHONPATH를 설정했는데 Python에서 모듈을 import할 수 없습니다.
환경 변수가 제대로 설정되었는지 확인하는 방법이 있나요?
```

```
라즈베리파이에서 nohup으로 실행한 스크립트가
SSH 연결 종료 후에도 계속 실행되나요?
```
