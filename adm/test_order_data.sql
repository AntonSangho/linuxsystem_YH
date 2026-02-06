-- ============================================================
-- 그누보드5 영카트 테스트 주문 데이터
-- 사용법: sudo mysql gnuboard5 < test_order_data.sql
-- ============================================================

-- 기존 테스트 데이터 정리 (필요시 주석 해제)
-- DELETE FROM g5_shop_order WHERE od_id LIKE 'TEST%';
-- DELETE FROM g5_shop_cart WHERE od_id LIKE 'TEST%';

-- ============================================================
-- 주문 데이터 (g5_shop_order)
-- ============================================================

-- 주문 1: 주문 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260201001', 'user01', '김민수', 'minsu@test.com', '02-1234-5678', '010-1111-2222',
    '06234', '', '서울특별시 강남구 테헤란로 123', '456호', '',
    '김민수', '02-1234-5678', '010-1111-2222',
    '06234', '', '서울특별시 강남구 테헤란로 123', '456호', '',
    '부재시 경비실에 맡겨주세요', '무통장', '주문',
    89000, 3000, 0, 0,
    '2026-02-01 09:30:00'
);

-- 주문 2: 입금 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260201002', 'user02', '이서연', 'seoyeon@test.com', '031-987-6543', '010-3333-4444',
    '13494', '', '경기도 성남시 분당구 판교로 256', '101동 802호', '',
    '이서연', '031-987-6543', '010-3333-4444',
    '13494', '', '경기도 성남시 분당구 판교로 256', '101동 802호', '',
    '', '카드', '입금',
    156000, 0, 5000, 1000,
    '2026-02-01 14:20:00'
);

-- 주문 3: 준비 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260202001', 'user03', '박지훈', 'jihun@test.com', '', '010-5555-6666',
    '48058', '', '부산광역시 해운대구 센텀중앙로 79', 'B동 1502호', '',
    '박지훈', '', '010-5555-6666',
    '48058', '', '부산광역시 해운대구 센텀중앙로 79', 'B동 1502호', '',
    '배송 전 연락 부탁드립니다', '카드', '준비',
    45000, 3000, 0, 2000,
    '2026-02-02 10:15:00'
);

-- 주문 4: 배송 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260202002', 'user04', '최수진', 'sujin@test.com', '042-111-2222', '010-7777-8888',
    '34126', '', '대전광역시 유성구 대학로 99', '가동 301호', '',
    '최수진', '042-111-2222', '010-7777-8888',
    '34126', '', '대전광역시 유성구 대학로 99', '가동 301호', '',
    '', '카드', '배송',
    234000, 0, 10000, 5000,
    '2026-02-02 16:45:00'
);

-- 주문 5: 완료 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260203001', 'user01', '김민수', 'minsu@test.com', '02-1234-5678', '010-1111-2222',
    '06234', '', '서울특별시 강남구 테헤란로 123', '456호', '',
    '김민수', '02-1234-5678', '010-1111-2222',
    '06234', '', '서울특별시 강남구 테헤란로 123', '456호', '',
    '', '무통장', '완료',
    32000, 3000, 0, 0,
    '2026-02-03 11:00:00'
);

-- 주문 6: 완료 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260203002', 'user05', '정하은', 'haeun@test.com', '', '010-9999-0000',
    '61452', '', '광주광역시 동구 금남로 200', '5층', '',
    '정하은', '', '010-9999-0000',
    '61452', '', '광주광역시 동구 금남로 200', '5층', '',
    '안전하게 포장해주세요', '카드', '완료',
    78000, 0, 3000, 0,
    '2026-02-03 13:30:00'
);

-- 주문 7: 취소 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260204001', 'user06', '한도윤', 'doyun@test.com', '', '010-2222-3333',
    '41585', '', '대구광역시 북구 대학로 80', '302호', '',
    '한도윤', '', '010-2222-3333',
    '41585', '', '대구광역시 북구 대학로 80', '302호', '',
    '', '무통장', '취소',
    55000, 3000, 0, 0,
    '2026-02-04 09:00:00'
);

-- 주문 8: 입금 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260204002', 'user02', '이서연', 'seoyeon@test.com', '031-987-6543', '010-3333-4444',
    '13494', '', '경기도 성남시 분당구 판교로 256', '101동 802호', '',
    '박준영', '', '010-4444-5555',
    '03781', '', '서울특별시 서대문구 연세로 50', '기숙사 201호', '',
    '선물용입니다. 영수증 빼주세요', '카드', '입금',
    128000, 0, 8000, 3000,
    '2026-02-04 15:20:00'
);

-- 주문 9: 주문 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260205001', 'user07', '윤지아', 'jia@test.com', '', '010-6666-7777',
    '21999', '', '인천광역시 연수구 송도과학로 32', '1203호', '',
    '윤지아', '', '010-6666-7777',
    '21999', '', '인천광역시 연수구 송도과학로 32', '1203호', '',
    '', '무통장', '주문',
    67000, 3000, 0, 0,
    '2026-02-05 08:45:00'
);

-- 주문 10: 배송 상태
INSERT INTO g5_shop_order (
    od_id, mb_id, od_name, od_email, od_tel, od_hp,
    od_zip1, od_zip2, od_addr1, od_addr2, od_addr3,
    od_b_name, od_b_tel, od_b_hp,
    od_b_zip1, od_b_zip2, od_b_addr1, od_b_addr2, od_b_addr3,
    od_memo, od_settle_case, od_status,
    od_receipt_price, od_send_cost, od_coupon, od_point,
    od_time
) VALUES (
    'TEST20260205002', 'user03', '박지훈', 'jihun@test.com', '', '010-5555-6666',
    '48058', '', '부산광역시 해운대구 센텀중앙로 79', 'B동 1502호', '',
    '박지훈', '', '010-5555-6666',
    '48058', '', '부산광역시 해운대구 센텀중앙로 79', 'B동 1502호', '',
    '문 앞에 놔주세요', '카드', '배송',
    199000, 0, 15000, 10000,
    '2026-02-05 12:10:00'
);

-- ============================================================
-- 주문 상품 데이터 (g5_shop_cart)
-- ============================================================

-- 주문 1 상품 (1건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260201001', 'user01', 'ITEM001', '프리미엄 무선 마우스', '색상: 블랙', 1, 45000, '주문', '2026-02-01 09:30:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260201001', 'user01', 'ITEM002', 'USB-C 허브 7포트', '', 1, 41000, '주문', '2026-02-01 09:30:00');

-- 주문 2 상품 (3건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260201002', 'user02', 'ITEM003', '기계식 키보드 청축', '레이아웃: 텐키리스', 1, 89000, '입금', '2026-02-01 14:20:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260201002', 'user02', 'ITEM004', '키보드 손목 받침대', '', 1, 25000, '입금', '2026-02-01 14:20:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260201002', 'user02', 'ITEM005', '키캡 세트 (파스텔)', '색상: 핑크', 2, 24000, '입금', '2026-02-01 14:20:00');

-- 주문 3 상품 (1건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260202001', 'user03', 'ITEM006', '27인치 모니터 암', '', 1, 44000, '준비', '2026-02-02 10:15:00');

-- 주문 4 상품 (2건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260202002', 'user04', 'ITEM007', 'QHD 게이밍 모니터 32인치', '', 1, 199000, '배송', '2026-02-02 16:45:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260202002', 'user04', 'ITEM008', 'HDMI 케이블 2m', '', 2, 8000, '배송', '2026-02-02 16:45:00');

-- 주문 5 상품 (1건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260203001', 'user01', 'ITEM009', 'LED 데스크 램프', '색상: 화이트', 1, 29000, '완료', '2026-02-03 11:00:00');

-- 주문 6 상품 (2건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260203002', 'user05', 'ITEM010', '노트북 파우치 15.6인치', '색상: 네이비', 1, 35000, '완료', '2026-02-03 13:30:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260203002', 'user05', 'ITEM001', '프리미엄 무선 마우스', '색상: 화이트', 1, 45000, '완료', '2026-02-03 13:30:00');

-- 주문 7 상품 (1건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260204001', 'user06', 'ITEM003', '기계식 키보드 청축', '레이아웃: 풀사이즈', 1, 52000, '취소', '2026-02-04 09:00:00');

-- 주문 8 상품 (3건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260204002', 'user02', 'ITEM007', 'QHD 게이밍 모니터 32인치', '', 1, 199000, '입금', '2026-02-04 15:20:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260204002', 'user02', 'ITEM008', 'HDMI 케이블 2m', '', 1, 8000, '입금', '2026-02-04 15:20:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260204002', 'user02', 'ITEM011', '모니터 클리너 세트', '', 1, 12000, '입금', '2026-02-04 15:20:00');

-- 주문 9 상품 (2건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260205001', 'user07', 'ITEM001', '프리미엄 무선 마우스', '색상: 블랙', 1, 45000, '주문', '2026-02-05 08:45:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260205001', 'user07', 'ITEM012', '마우스 패드 XL', '', 1, 19000, '주문', '2026-02-05 08:45:00');

-- 주문 10 상품 (2건)
INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260205002', 'user03', 'ITEM013', '무선 게이밍 헤드셋', '', 1, 159000, '배송', '2026-02-05 12:10:00');

INSERT INTO g5_shop_cart (od_id, mb_id, it_id, it_name, ct_option, ct_qty, ct_price, ct_status, ct_time)
VALUES ('TEST20260205002', 'user03', 'ITEM014', '헤드셋 거치대', '색상: 블랙', 1, 25000, '배송', '2026-02-05 12:10:00');
