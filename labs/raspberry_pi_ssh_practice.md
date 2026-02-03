# 라즈베리파이 SSH 접속 실습 가이드

## 개요

라즈베리파이에 SSH로 원격 접속하여 수행할 수 있는 다양한 실습을 정리했습니다.
Day 3(셸), Day 4(권한/프로세스) 내용을 실제 임베디드 환경에서 경험합니다.

---

## 사전 준비

### 라즈베리파이 SSH 활성화

```bash
# 라즈베리파이에서 직접 (모니터/키보드 연결 시)
sudo raspi-config
# → Interface Options → SSH → Enable

# 또는 명령어로
sudo systemctl enable ssh
sudo systemctl start ssh
```

### IP 주소 확인

```bash
# 라즈베리파이에서
hostname -I

# 또는
ip addr show wlan0   # WiFi
ip addr show eth0    # 유선
```

### Windows에서 SSH 접속

```powershell
# PowerShell 또는 CMD
ssh pi@192.168.x.x

# 기본 비밀번호: raspberry
```

---

## 실습 1: 시스템 정보 확인 (15분)

### 라즈베리파이 하드웨어 정보

```bash
# 모델 확인
cat /proc/device-tree/model

# CPU 정보
lscpu
cat /proc/cpuinfo

# 메모리
free -h
cat /proc/meminfo | head -10

# SD 카드 / 저장소
df -h
lsblk

# USB 장치
lsusb

# 네트워크 인터페이스
ip link show
```

### CPU 온도 모니터링

```bash
# 온도 읽기 (밀리섭씨)
cat /sys/class/thermal/thermal_zone0/temp

# 섭씨로 변환해서 보기
echo "CPU Temp: $(($(cat /sys/class/thermal/thermal_zone0/temp)/1000))°C"

# 실시간 모니터링 (2초마다)
watch -n 2 'echo "CPU: $(($(cat /sys/class/thermal/thermal_zone0/temp)/1000))°C"'
```

---

## 실습 2: 환경 변수 활용 (20분)

### 라즈베리파이 전용 환경 변수 설정

```bash
# ROS2 개발을 위한 환경 변수 예시
cat << 'EOF' >> ~/.bashrc

# Raspberry Pi Development Environment
export RPI_HOME=/home/pi
export WORKSPACE=$RPI_HOME/ros2_ws
export SCRIPTS=$RPI_HOME/scripts

# 라즈베리파이 유틸리티 alias
alias temp='echo "CPU: $(($(cat /sys/class/thermal/thermal_zone0/temp)/1000))°C"'
alias mem='free -h | head -2'
alias disk='df -h /'
alias myip='hostname -I'
EOF

source ~/.bashrc
```

### 환경 변수 테스트

```bash
# 설정 확인
echo $RPI_HOME
echo $WORKSPACE

# alias 테스트
temp
mem
disk
myip
```

---

## 실습 3: 프로세스 모니터링 (25분)

### 리소스 모니터링 도구 설치

```bash
# htop 설치 (향상된 top)
sudo apt install htop -y

# 실행
htop
# q로 종료
```

### CPU 부하 테스트

```bash
# stress 패키지 설치
sudo apt install stress -y

# 터미널 1: 모니터링
htop

# 터미널 2 (새 SSH 세션): CPU 부하 발생
stress --cpu 2 --timeout 30

# 30초 후 자동 종료, htop에서 CPU 사용률 변화 관찰
```

### Python 프로세스 모니터링

```bash
# 테스트용 Python 스크립트
cat << 'EOF' > ~/test_process.py
#!/usr/bin/env python3
import time
import os

print(f"Process started. PID: {os.getpid()}")
for i in range(60):
    print(f"Running... {i+1}/60")
    time.sleep(1)
print("Process finished")
EOF

chmod +x ~/test_process.py

# 백그라운드 실행
python3 ~/test_process.py &

# PID 확인
echo "PID: $!"

# 프로세스 확인
ps aux | grep test_process

# 프로세스 종료
kill $!
```

---

## 실습 4: 권한 관리 실습 (20분)

### 개발용 디렉토리 구조 만들기

```bash
# 프로젝트 디렉토리 생성
mkdir -p ~/project/{src,bin,config,logs}

# 각 디렉토리별 권한 설정
chmod 755 ~/project           # 기본 접근
chmod 755 ~/project/src       # 소스코드
chmod 700 ~/project/bin       # 실행파일 (소유자만)
chmod 600 ~/project/config    # 설정파일 (비공개)
chmod 755 ~/project/logs      # 로그 읽기 허용

# 권한 확인
ls -la ~/project/
```

### 스크립트 권한 실습

```bash
# 실행 불가 스크립트
echo 'echo "Hello"' > ~/project/bin/test.sh
ls -l ~/project/bin/test.sh
# 실행 시도 (실패)
~/project/bin/test.sh

# 실행 권한 부여
chmod +x ~/project/bin/test.sh
# 다시 실행 (성공)
~/project/bin/test.sh
```

---

## 실습 5: 셸 스크립트 작성 (30분)

### 라즈베리파이 상태 체크 스크립트

```bash
mkdir -p ~/scripts

cat << 'EOF' > ~/scripts/rpi_health.sh
#!/bin/bash
#
# 라즈베리파이 헬스 체크 스크립트
# 사용법: ./rpi_health.sh [--log]
#

LOG_MODE=false
LOG_FILE=~/project/logs/health_$(date +%Y%m%d).log

if [ "$1" == "--log" ]; then
    LOG_MODE=true
fi

output() {
    echo "$1"
    if [ "$LOG_MODE" == true ]; then
        echo "$(date '+%H:%M:%S') $1" >> $LOG_FILE
    fi
}

output "========================================"
output "  Raspberry Pi Health Check"
output "  $(date '+%Y-%m-%d %H:%M:%S')"
output "========================================"
output ""

# CPU 온도
if [ -f /sys/class/thermal/thermal_zone0/temp ]; then
    TEMP=$(($(cat /sys/class/thermal/thermal_zone0/temp)/1000))
    if [ $TEMP -gt 70 ]; then
        output "[WARNING] CPU Temperature: ${TEMP}°C (HIGH!)"
    elif [ $TEMP -gt 60 ]; then
        output "[CAUTION] CPU Temperature: ${TEMP}°C"
    else
        output "[OK] CPU Temperature: ${TEMP}°C"
    fi
fi

# 메모리
MEM_TOTAL=$(free -m | awk 'NR==2{print $2}')
MEM_USED=$(free -m | awk 'NR==2{print $3}')
MEM_PERCENT=$((MEM_USED * 100 / MEM_TOTAL))

if [ $MEM_PERCENT -gt 90 ]; then
    output "[WARNING] Memory: ${MEM_USED}MB / ${MEM_TOTAL}MB (${MEM_PERCENT}%)"
else
    output "[OK] Memory: ${MEM_USED}MB / ${MEM_TOTAL}MB (${MEM_PERCENT}%)"
fi

# 디스크
DISK_PERCENT=$(df / | awk 'NR==2{print $5}' | tr -d '%')

if [ $DISK_PERCENT -gt 90 ]; then
    output "[WARNING] Disk: ${DISK_PERCENT}% used"
else
    output "[OK] Disk: ${DISK_PERCENT}% used"
fi

# CPU 부하
LOAD=$(cat /proc/loadavg | awk '{print $1}')
output "[INFO] CPU Load (1min): $LOAD"

# 네트워크
IP=$(hostname -I | awk '{print $1}')
output "[INFO] IP Address: $IP"

output ""
output "========================================"

if [ "$LOG_MODE" == true ]; then
    output "Log saved to: $LOG_FILE"
fi
EOF

chmod +x ~/scripts/rpi_health.sh

# 실행
~/scripts/rpi_health.sh

# 로그 모드로 실행
~/scripts/rpi_health.sh --log
cat ~/project/logs/health_*.log
```

### 백그라운드 모니터링 데몬

```bash
cat << 'EOF' > ~/scripts/rpi_daemon.sh
#!/bin/bash
#
# 라즈베리파이 모니터링 데몬
# 5분마다 상태를 기록
#

LOG_DIR=~/project/logs
INTERVAL=300  # 5분

mkdir -p $LOG_DIR

echo "Daemon started at $(date)" > $LOG_DIR/daemon.log
echo "PID: $$" >> $LOG_DIR/daemon.log

while true; do
    TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
    TEMP=$(($(cat /sys/class/thermal/thermal_zone0/temp 2>/dev/null)/1000))
    MEM=$(free -m | awk 'NR==2{printf "%.1f", $3*100/$2}')
    DISK=$(df / | awk 'NR==2{print $5}')

    echo "$TIMESTAMP | Temp: ${TEMP:-N/A}°C | Mem: ${MEM}% | Disk: $DISK" >> $LOG_DIR/monitor.log

    sleep $INTERVAL
done
EOF

chmod +x ~/scripts/rpi_daemon.sh

# nohup으로 실행
nohup ~/scripts/rpi_daemon.sh > /dev/null 2>&1 &
echo "Daemon PID: $!"

# 확인
ps aux | grep rpi_daemon
tail -f ~/project/logs/monitor.log

# 종료 시
# pkill -f rpi_daemon.sh
```

---

## 실습 6: Python 개발 환경 (25분)

### Python 패키지 설치

```bash
# pip 업그레이드
sudo pip3 install --upgrade pip

# 유용한 패키지 설치
pip3 install psutil   # 시스템 정보
pip3 install gpiozero # GPIO 제어 (간편)
```

### Python 시스템 모니터

```bash
cat << 'EOF' > ~/scripts/py_monitor.py
#!/usr/bin/env python3
"""Python으로 작성한 시스템 모니터"""

import os
import sys
import time

try:
    import psutil
except ImportError:
    print("Installing psutil...")
    os.system("pip3 install psutil")
    import psutil

def get_cpu_temp():
    """CPU 온도 읽기"""
    try:
        with open('/sys/class/thermal/thermal_zone0/temp', 'r') as f:
            return int(f.read().strip()) / 1000
    except:
        return None

def main():
    print("=" * 50)
    print("  Python System Monitor")
    print("=" * 50)
    print()

    # CPU
    print(f"[CPU]")
    print(f"  Usage: {psutil.cpu_percent(interval=1)}%")
    print(f"  Cores: {psutil.cpu_count()}")
    temp = get_cpu_temp()
    if temp:
        print(f"  Temperature: {temp:.1f}°C")
    print()

    # Memory
    mem = psutil.virtual_memory()
    print(f"[Memory]")
    print(f"  Total: {mem.total / (1024**3):.2f} GB")
    print(f"  Used: {mem.used / (1024**3):.2f} GB ({mem.percent}%)")
    print(f"  Available: {mem.available / (1024**3):.2f} GB")
    print()

    # Disk
    disk = psutil.disk_usage('/')
    print(f"[Disk (/)]")
    print(f"  Total: {disk.total / (1024**3):.2f} GB")
    print(f"  Used: {disk.used / (1024**3):.2f} GB ({disk.percent}%)")
    print(f"  Free: {disk.free / (1024**3):.2f} GB")
    print()

    # Network
    print(f"[Network]")
    net = psutil.net_if_addrs()
    for iface, addrs in net.items():
        for addr in addrs:
            if addr.family == 2:  # IPv4
                print(f"  {iface}: {addr.address}")
    print()

    # Top Processes
    print(f"[Top 5 Processes by Memory]")
    procs = []
    for proc in psutil.process_iter(['pid', 'name', 'memory_percent']):
        try:
            procs.append(proc.info)
        except:
            pass
    procs = sorted(procs, key=lambda x: x['memory_percent'] or 0, reverse=True)[:5]
    for p in procs:
        print(f"  PID {p['pid']:5d}: {p['name'][:20]:20s} ({p['memory_percent']:.1f}%)")

    print()
    print("=" * 50)

if __name__ == "__main__":
    main()
EOF

chmod +x ~/scripts/py_monitor.py
python3 ~/scripts/py_monitor.py
```

---

## 실습 7: cron 작업 설정 (15분)

### 주기적 작업 등록

```bash
# 현재 cron 작업 확인
crontab -l

# cron 편집
crontab -e

# 아래 내용 추가 (매시 정각에 헬스체크)
# 0 * * * * /home/pi/scripts/rpi_health.sh --log

# 저장 후 종료

# cron 작업 확인
crontab -l

# cron 서비스 상태
systemctl status cron
```

### cron 형식

```
분  시  일  월  요일  명령어
*   *   *   *   *     command

예시:
0 * * * *      # 매시 정각
*/5 * * * *    # 5분마다
0 9 * * 1      # 매주 월요일 9시
0 0 1 * *      # 매월 1일 자정
```

---

## 정리 및 제출

### 실습 완료 체크리스트

- [ ] SSH 접속 성공
- [ ] 시스템 정보 확인 (모델, CPU, 메모리)
- [ ] 환경 변수 설정 (~/.bashrc)
- [ ] rpi_health.sh 스크립트 작성 및 실행
- [ ] py_monitor.py 실행 성공
- [ ] 백그라운드 프로세스 관리

### 제출물

```bash
# 결과물 압축
cd ~
tar -czvf rpi_practice_result.tar.gz \
    scripts/ \
    project/logs/ \
    .bashrc

# 파일 확인
ls -la rpi_practice_result.tar.gz

# scp로 다운로드 (로컬 PC에서 실행)
# scp pi@192.168.x.x:~/rpi_practice_result.tar.gz .
```

---

## 문제 해결

### SSH 연결이 느림

```bash
# 라즈베리파이에서 DNS 역조회 비활성화
sudo nano /etc/ssh/sshd_config
# UseDNS no 추가

sudo systemctl restart ssh
```

### 명령어가 없다고 나옴

```bash
# PATH 확인
echo $PATH

# 명령어 위치 찾기
which python3
whereis htop

# 패키지 설치
sudo apt install <패키지명>
```

### SSH 연결이 끊기면 프로세스 종료

```bash
# nohup 사용
nohup ./script.sh &

# 또는 screen 사용
screen -S mysession
./script.sh
# Ctrl+A, D로 분리

# 재접속 후
screen -r mysession
```
