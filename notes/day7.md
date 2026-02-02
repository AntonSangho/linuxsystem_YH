# Day 7: 디스크와 파일 시스템

## 수업 개요

| 항목 | 내용 |
|------|------|
| 총 시간 | 5시간 |
| 교재 범위 | 파일 시스템, 마운트, 디스크 관리 |
| 실습과제 | 디스크 용량 확인 및 마운트 실습 |

## 학습 목표

- 리눅스 파일 시스템 종류와 구조 이해
- 마운트/언마운트 개념 숙달
- df, du 명령어로 디스크 관리

---

## 시간표

| 시간 | 내용 |
|------|------|
| 0.5h | 디스크 기반 파일 시스템 종류 |
| 0.5h | 리눅스 파일 시스템 구조 |
| 1h | 파일 시스템 마운트 |
| 1h | 디스크 추가 설치 |
| 1h | 디스크 파티션: fdisk |
| 1h | 디스크 관리: df, du |

---

## 1. 디스크 기반 파일 시스템 종류 (30분)

### 리눅스 파일 시스템

| 파일 시스템 | 설명 |
|-------------|------|
| ext4 | 리눅스 기본 파일 시스템 (Ubuntu 기본) |
| ext3 | ext4 이전 버전, 저널링 지원 |
| xfs | 대용량 파일 시스템, RHEL/CentOS 기본 |
| btrfs | 차세대 파일 시스템, 스냅샷 지원 |

### 기타 파일 시스템

| 파일 시스템 | 설명 |
|-------------|------|
| ntfs | Windows 파일 시스템 |
| fat32/vfat | USB, SD카드 (범용) |
| swap | 스왑 영역 |

### 파일 시스템 확인

```bash
# 마운트된 파일 시스템 확인
df -T

# 특정 디스크 파일 시스템 확인
lsblk -f
```

---

## 2. 리눅스 파일 시스템 구조 (30분)

### 디렉토리 구조 (복습)

```
/
├── bin      # 기본 명령어
├── boot     # 부팅 관련 파일
├── dev      # 장치 파일
├── etc      # 설정 파일
├── home     # 사용자 홈 디렉토리
├── mnt      # 임시 마운트 포인트
├── media    # 자동 마운트 (USB 등)
├── tmp      # 임시 파일
├── usr      # 사용자 프로그램
└── var      # 가변 데이터 (로그 등)
```

### 장치 파일 (디스크)

```bash
ls /dev/sd*
# /dev/sda   - 첫 번째 디스크
# /dev/sda1  - 첫 번째 디스크의 첫 번째 파티션
# /dev/sda2  - 첫 번째 디스크의 두 번째 파티션
# /dev/sdb   - 두 번째 디스크
```

| 장치명 | 설명 |
|--------|------|
| /dev/sda | 첫 번째 SATA/SCSI 디스크 |
| /dev/sdb | 두 번째 디스크 |
| /dev/nvme0n1 | NVMe SSD |
| /dev/vda | 가상머신 디스크 |

---

## 3. 파일 시스템 마운트 (1시간)

### 마운트란?

- 디스크/파티션을 디렉토리에 연결하는 것
- 연결된 디렉토리를 통해 디스크에 접근

### mount 명령어

```bash
# 현재 마운트 상태 확인
mount

# 간단히 보기
mount | grep "^/dev"

# 디스크 마운트
sudo mount /dev/sdb1 /mnt/data

# 읽기 전용으로 마운트
sudo mount -o ro /dev/sdb1 /mnt/data
```

### umount 명령어

```bash
# 언마운트
sudo umount /mnt/data

# 또는 장치명으로
sudo umount /dev/sdb1
```

### 마운트 포인트 생성

```bash
# 마운트할 디렉토리 생성
sudo mkdir -p /mnt/data
sudo mkdir -p /mnt/backup
```

### 부팅 시 자동 마운트: /etc/fstab

```bash
# fstab 확인
cat /etc/fstab
```

```
# /etc/fstab 형식
# 장치          마운트포인트  파일시스템  옵션     덤프  검사순서
/dev/sdb1      /mnt/data    ext4       defaults  0     2
```

---

## 4. 디스크 추가 설치 (1시간)

### 디스크 추가 과정 (개요)

1. 물리적 디스크 연결 (또는 VM에서 디스크 추가)
2. 디스크 확인: `lsblk`
3. 파티션 생성: `fdisk`
4. 파일 시스템 생성: `mkfs`
5. 마운트: `mount`
6. 영구 마운트: `/etc/fstab` 수정

### 디스크 확인

```bash
# 연결된 디스크 확인
lsblk

# 상세 정보
sudo fdisk -l

# 블록 장치 정보
lsblk -f
```

### 파일 시스템 생성 (mkfs)

```bash
# ext4 파일 시스템 생성
sudo mkfs.ext4 /dev/sdb1

# xfs 파일 시스템 생성
sudo mkfs.xfs /dev/sdb1
```

---

## 5. 디스크 파티션 나누기: fdisk (1시간)

### fdisk 기본 사용법

```bash
# fdisk 시작
sudo fdisk /dev/sdb
```

### fdisk 내부 명령어

| 명령 | 설명 |
|------|------|
| m | 도움말 |
| p | 파티션 테이블 출력 |
| n | 새 파티션 생성 |
| d | 파티션 삭제 |
| t | 파티션 타입 변경 |
| w | 변경사항 저장 후 종료 |
| q | 저장 없이 종료 |

### fdisk 사용 예시

```bash
sudo fdisk /dev/sdb

# 1. p 입력 - 현재 파티션 확인
# 2. n 입력 - 새 파티션 생성
#    - p (primary) 선택
#    - 파티션 번호: 1
#    - 시작 섹터: Enter (기본값)
#    - 끝 섹터: Enter (전체 사용) 또는 +10G (10GB)
# 3. p 입력 - 파티션 확인
# 4. w 입력 - 저장 후 종료
```

### 파티션 생성 후 과정

```bash
# 1. 파티션 확인
lsblk

# 2. 파일 시스템 생성
sudo mkfs.ext4 /dev/sdb1

# 3. 마운트 포인트 생성
sudo mkdir -p /mnt/newdisk

# 4. 마운트
sudo mount /dev/sdb1 /mnt/newdisk

# 5. 확인
df -h /mnt/newdisk
```

---

## 6. 디스크 관리: df, du (1시간)

### df - 디스크 사용량 확인

```bash
# 기본 출력
df

# 읽기 쉬운 단위 (-h: human readable)
df -h

# 파일 시스템 타입 포함
df -Th

# 특정 경로만
df -h /home
```

### df 출력 해석

```
Filesystem      Size  Used Avail Use% Mounted on
/dev/sda1        50G   15G   32G  32% /
```

| 필드 | 설명 |
|------|------|
| Filesystem | 장치명 |
| Size | 전체 크기 |
| Used | 사용량 |
| Avail | 남은 용량 |
| Use% | 사용률 |
| Mounted on | 마운트 위치 |

### du - 디렉토리/파일 크기 확인

```bash
# 현재 디렉토리 크기
du -sh .

# 하위 디렉토리별 크기
du -h --max-depth=1

# 특정 디렉토리
du -sh /var/log

# 크기순 정렬
du -h /home | sort -h
```

### 실무 활용 예제

```bash
# 디스크 사용률 80% 이상인지 확인
df -h | grep -E "([8-9][0-9]|100)%"

# 홈 디렉토리에서 큰 폴더 찾기
du -h ~ --max-depth=1 | sort -h | tail -10

# /var/log 크기 확인 (로그 정리 필요 여부)
du -sh /var/log/*

# 용량 부족 시 큰 파일 찾기
sudo find / -type f -size +100M 2>/dev/null
```

---

## 7. 실습과제 7: 디스크 관리 (1시간)

### 과제 목표

- df, du 명령어 숙달
- 디스크 사용량 모니터링

### 수행 단계

#### Part 1: df로 디스크 확인

```bash
# 전체 디스크 상태
df -h

# 파일 시스템 타입 포함
df -Th

# 루트 파티션만
df -h /
```

#### Part 2: du로 디렉토리 크기 확인

```bash
# 홈 디렉토리 크기
du -sh ~

# 하위 디렉토리별 크기
du -h ~ --max-depth=1

# 크기순 정렬
du -h ~ --max-depth=1 | sort -h
```

#### Part 3: 큰 파일/디렉토리 찾기

```bash
# /var 디렉토리 분석
sudo du -sh /var/*

# 큰 로그 파일 찾기
sudo du -sh /var/log/* | sort -h | tail -5
```

#### Part 4: 마운트 상태 확인

```bash
# 마운트 상태
mount | grep "^/dev"

# 블록 장치 정보
lsblk
```

### 제출 내용

- `df -h` 결과 캡처
- `du -h ~ --max-depth=1 | sort -h` 결과 캡처
- `lsblk` 결과 캡처

### 평가 기준

| 항목 | 배점 |
|------|------|
| df 명령어 활용 | 30% |
| du 명령어 활용 | 30% |
| 마운트 상태 확인 | 20% |
| 결과 분석 | 20% |

---

## 예상 질문 및 답변

### Q: df와 du 차이는?
**A**: df는 파일 시스템(파티션) 전체 사용량, du는 특정 디렉토리/파일 크기. 용량 부족 시 df로 전체 확인, du로 어디서 많이 쓰는지 찾음.

### Q: 마운트와 언마운트는 언제 하나요?
**A**: USB, 외장하드, 추가 디스크 연결 시 마운트. 안전하게 제거하려면 언마운트 필수.

### Q: /etc/fstab 잘못 수정하면 어떻게 되나요?
**A**: 부팅 실패할 수 있음. 수정 전 백업 필수. 복구 모드로 부팅 후 수정 가능.

---

## 다음 수업 예고

**Day 8**: (교재 내용에 따라 결정)
