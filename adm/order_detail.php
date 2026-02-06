<?php
if (!defined('_GNUBOARD_')) exit;

$sub_menu = '400100';
include_once('./_common.php');

// 읽기 권한 체크
auth_check($auth[$sub_menu], "r");

// ============================================================
// 주문번호 파라미터 처리
// ============================================================
$od_id = isset($_GET['od_id']) ? clean_xss_tags($_GET['od_id']) : '';

if (!$od_id) {
    alert('잘못된 접근입니다.', './order_list.php');
}

// ============================================================
// 주문 데이터 조회
// ============================================================
$sql = "SELECT * FROM {$g5['g5_shop_order_table']}
        WHERE od_id = '".sql_escape_string($od_id)."'";
$od = sql_fetch($sql);

if (!$od) {
    alert('주문 데이터를 찾을 수 없습니다.', './order_list.php');
}

// ============================================================
// 주문 상품 목록 조회 (장바구니 테이블)
// ============================================================
$sql_cart = "SELECT * FROM {$g5['g5_shop_cart_table']}
             WHERE od_id = '".sql_escape_string($od_id)."'
             ORDER BY ct_id ASC";
$result_cart = sql_query($sql_cart);

// 상품 합계 계산
$total_item_price = 0;
$total_item_count = 0;

$g5['title'] = '주문 상세';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<!-- ============================================================ -->
<!-- 페이지 설명 -->
<!-- ============================================================ -->
<div class="local_desc01">
    <p><strong>주문 상세 정보를 확인합니다.</strong></p>
</div>

<!-- ============================================================ -->
<!-- 액션 버튼 -->
<!-- ============================================================ -->
<div class="btn_fixed_top">
    <button type="button" class="btn btn_01" onclick="openStatusModal()">상태 변경</button>
    <button type="button" class="btn btn_02" onclick="deleteOrder()">주문 삭제</button>
</div>

<!-- ============================================================ -->
<!-- 주문 기본 정보 -->
<!-- ============================================================ -->
<h2 class="h2_tit">주문 기본 정보</h2>

<table class="tbl_frm01">
    <colgroup>
        <col style="width:150px;">
        <col>
        <col style="width:150px;">
        <col>
    </colgroup>
    <tbody>
        <tr>
            <th>주문번호</th>
            <td><?php echo htmlspecialchars($od['od_id']); ?></td>
            <th>주문상태</th>
            <td>
                <?php
                switch($od['od_status']) {
                    case '주문':
                        echo '<span class="badge_order">주문</span>';
                        break;
                    case '입금':
                        echo '<span class="badge_use">입금</span>';
                        break;
                    case '준비':
                        echo '<span class="badge_yes">준비</span>';
                        break;
                    case '배송':
                        echo '<span class="badge_use">배송</span>';
                        break;
                    case '완료':
                        echo '<span class="badge_use">완료</span>';
                        break;
                    case '취소':
                        echo '<span class="badge_notuse">취소</span>';
                        break;
                    default:
                        echo htmlspecialchars($od['od_status']);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th>주문일시</th>
            <td><?php echo $od['od_time']; ?></td>
            <th>결제방법</th>
            <td><?php echo htmlspecialchars($od['od_settle_case']); ?></td>
        </tr>
        <tr>
            <th>결제금액</th>
            <td colspan="3"><strong style="color:#ff4081; font-size:1.2em;"><?php echo number_format($od['od_receipt_price']); ?>원</strong></td>
        </tr>
    </tbody>
</table>

<!-- ============================================================ -->
<!-- 주문자 정보 -->
<!-- ============================================================ -->
<h2 class="h2_tit" style="margin-top:30px;">주문자 정보</h2>

<table class="tbl_frm01">
    <colgroup>
        <col style="width:150px;">
        <col>
        <col style="width:150px;">
        <col>
    </colgroup>
    <tbody>
        <tr>
            <th>주문자명</th>
            <td><?php echo htmlspecialchars($od['od_name']); ?></td>
            <th>회원ID</th>
            <td><?php echo htmlspecialchars($od['mb_id']); ?></td>
        </tr>
        <tr>
            <th>전화번호</th>
            <td><?php echo htmlspecialchars($od['od_tel']); ?></td>
            <th>휴대폰</th>
            <td><?php echo htmlspecialchars($od['od_hp']); ?></td>
        </tr>
        <tr>
            <th>이메일</th>
            <td colspan="3"><?php echo htmlspecialchars($od['od_email']); ?></td>
        </tr>
        <tr>
            <th>주소</th>
            <td colspan="3">
                [<?php echo htmlspecialchars($od['od_zip1'].$od['od_zip2']); ?>]
                <?php echo htmlspecialchars($od['od_addr1']); ?>
                <?php echo htmlspecialchars($od['od_addr2']); ?>
                <?php echo htmlspecialchars($od['od_addr3']); ?>
            </td>
        </tr>
    </tbody>
</table>

<!-- ============================================================ -->
<!-- 배송지 정보 -->
<!-- ============================================================ -->
<h2 class="h2_tit" style="margin-top:30px;">배송지 정보</h2>

<table class="tbl_frm01">
    <colgroup>
        <col style="width:150px;">
        <col>
        <col style="width:150px;">
        <col>
    </colgroup>
    <tbody>
        <tr>
            <th>수령인</th>
            <td><?php echo htmlspecialchars($od['od_b_name']); ?></td>
            <th>휴대폰</th>
            <td><?php echo htmlspecialchars($od['od_b_hp']); ?></td>
        </tr>
        <tr>
            <th>전화번호</th>
            <td colspan="3"><?php echo htmlspecialchars($od['od_b_tel']); ?></td>
        </tr>
        <tr>
            <th>배송 주소</th>
            <td colspan="3">
                [<?php echo htmlspecialchars($od['od_b_zip1'].$od['od_b_zip2']); ?>]
                <?php echo htmlspecialchars($od['od_b_addr1']); ?>
                <?php echo htmlspecialchars($od['od_b_addr2']); ?>
                <?php echo htmlspecialchars($od['od_b_addr3']); ?>
            </td>
        </tr>
        <tr>
            <th>배송 메모</th>
            <td colspan="3">
                <?php
                if ($od['od_memo']) {
                    echo nl2br(htmlspecialchars($od['od_memo']));
                } else {
                    echo '<span style="color:#999;">메모가 없습니다.</span>';
                }
                ?>
            </td>
        </tr>
    </tbody>
</table>

<!-- ============================================================ -->
<!-- 주문 상품 목록 -->
<!-- ============================================================ -->
<h2 class="h2_tit" style="margin-top:30px;">주문 상품</h2>

<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
        <tr>
            <th scope="col" class="td_num">번호</th>
            <th scope="col">상품명</th>
            <th scope="col">옵션</th>
            <th scope="col" class="td_num">수량</th>
            <th scope="col" class="td_num">단가</th>
            <th scope="col" class="td_num">소계</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $cart_num = 0;
    for ($i=0; $ct=sql_fetch_array($result_cart); $i++) {
        $bg = 'bg'.($i%2);
        $cart_num++;
        $subtotal = $ct['ct_price'] * $ct['ct_qty'];
        $total_item_price += $subtotal;
        $total_item_count += $ct['ct_qty'];
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?php echo $cart_num; ?></td>
        <td class="td_left"><?php echo htmlspecialchars($ct['it_name']); ?></td>
        <td class="td_left">
            <?php
            if ($ct['ct_option']) {
                echo htmlspecialchars($ct['ct_option']);
            } else {
                echo '-';
            }
            ?>
        </td>
        <td class="td_num"><?php echo number_format($ct['ct_qty']); ?></td>
        <td class="td_num"><?php echo number_format($ct['ct_price']); ?>원</td>
        <td class="td_num"><?php echo number_format($subtotal); ?>원</td>
    </tr>
    <?php
    }
    if ($cart_num == 0) {
        echo '<tr><td colspan="6" class="empty_table">주문 상품이 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<!-- ============================================================ -->
<!-- 결제 요약 -->
<!-- ============================================================ -->
<h2 class="h2_tit" style="margin-top:30px;">결제 요약</h2>

<table class="tbl_frm01">
    <colgroup>
        <col style="width:150px;">
        <col>
        <col style="width:150px;">
        <col>
    </colgroup>
    <tbody>
        <tr>
            <th>총 상품금액</th>
            <td><?php echo number_format($total_item_price); ?>원 (<?php echo number_format($total_item_count); ?>개)</td>
            <th>배송비</th>
            <td><?php echo number_format($od['od_send_cost']); ?>원</td>
        </tr>
        <tr>
            <th>쿠폰할인</th>
            <td><?php echo $od['od_coupon'] ? '-'.number_format($od['od_coupon']).'원' : '0원'; ?></td>
            <th>포인트 사용</th>
            <td><?php echo $od['od_point'] ? '-'.number_format($od['od_point']).'P' : '0P'; ?></td>
        </tr>
        <tr>
            <th>결제방법</th>
            <td><?php echo htmlspecialchars($od['od_settle_case']); ?></td>
            <th>최종 결제금액</th>
            <td><strong style="color:#ff4081; font-size:1.2em;"><?php echo number_format($od['od_receipt_price']); ?>원</strong></td>
        </tr>
    </tbody>
</table>

<!-- ============================================================ -->
<!-- 하단 버튼 -->
<!-- ============================================================ -->
<div class="btn_confirm">
    <a href="./order_list.php" class="btn btn_02">목록</a>
    <button type="button" class="btn btn_01" onclick="openStatusModal()">상태 변경</button>
</div>

<!-- ============================================================ -->
<!-- 주문상태 변경 모달 -->
<!-- ============================================================ -->
<div class="modal_overlay" id="statusModal">
    <div class="modal_container" style="max-width:450px;">
        <div class="modal_header">
            <h3>주문상태 변경</h3>
            <button type="button" class="modal_close" onclick="closeStatusModal()">&times;</button>
        </div>
        <div class="modal_body">
            <form id="statusForm">
                <input type="hidden" name="od_id" value="<?php echo htmlspecialchars($od['od_id']); ?>">
                <table class="tbl_frm01">
                    <tr>
                        <th style="width:100px;">현재 상태</th>
                        <td>
                            <?php
                            switch($od['od_status']) {
                                case '주문':
                                    echo '<span class="badge_order">주문</span>';
                                    break;
                                case '입금':
                                    echo '<span class="badge_use">입금</span>';
                                    break;
                                case '준비':
                                    echo '<span class="badge_yes">준비</span>';
                                    break;
                                case '배송':
                                    echo '<span class="badge_use">배송</span>';
                                    break;
                                case '완료':
                                    echo '<span class="badge_use">완료</span>';
                                    break;
                                case '취소':
                                    echo '<span class="badge_notuse">취소</span>';
                                    break;
                                default:
                                    echo htmlspecialchars($od['od_status']);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>변경 상태 <strong class="sound_only">필수</strong></th>
                        <td>
                            <select name="od_status" id="od_status_select" class="frm_input" style="width:100%;" required>
                                <option value="">선택하세요</option>
                                <option value="주문"<?php echo $od['od_status']=='주문'?' selected':''; ?>>주문</option>
                                <option value="입금"<?php echo $od['od_status']=='입금'?' selected':''; ?>>입금</option>
                                <option value="준비"<?php echo $od['od_status']=='준비'?' selected':''; ?>>준비</option>
                                <option value="배송"<?php echo $od['od_status']=='배송'?' selected':''; ?>>배송</option>
                                <option value="완료"<?php echo $od['od_status']=='완료'?' selected':''; ?>>완료</option>
                                <option value="취소"<?php echo $od['od_status']=='취소'?' selected':''; ?>>취소</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="modal_footer">
            <button type="button" class="btn_cancel" onclick="closeStatusModal()">취소</button>
            <button type="button" class="btn_save" onclick="changeStatus()">변경</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JavaScript -->
<!-- ============================================================ -->
<script>
// 상태 변경 모달 열기
function openStatusModal() {
    $('#statusModal').fadeIn();
}

// 상태 변경 모달 닫기
function closeStatusModal() {
    $('#statusModal').fadeOut();
}

// ESC 키로 모달 닫기
$(document).keyup(function(e) {
    if (e.key === "Escape") closeStatusModal();
});

// 배경 클릭시 모달 닫기
$('#statusModal').click(function(e) {
    if (e.target === this) closeStatusModal();
});

// 상태 변경
function changeStatus() {
    var newStatus = $('#od_status_select').val();
    if (!newStatus) {
        alert('변경할 상태를 선택하세요.');
        return;
    }

    if (!confirm('주문상태를 "' + newStatus + '"(으)로 변경하시겠습니까?')) return;

    $.ajax({
        url: './order_ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'change_status',
            od_id: '<?php echo htmlspecialchars($od['od_id'], ENT_QUOTES); ?>',
            od_status: newStatus
        },
        success: function(res) {
            alert(res.message);
            if (res.success) {
                location.reload();
            }
        },
        error: function() {
            alert('상태 변경에 실패했습니다.');
        }
    });
}

// 주문 삭제
function deleteOrder() {
    if (!confirm('이 주문을 삭제하시겠습니까?\n삭제된 주문은 복구할 수 없습니다.')) return;

    $.ajax({
        url: './order_ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'delete',
            od_id: '<?php echo htmlspecialchars($od['od_id'], ENT_QUOTES); ?>'
        },
        success: function(res) {
            alert(res.message);
            if (res.success) {
                location.href = './order_list.php';
            }
        },
        error: function() {
            alert('삭제에 실패했습니다.');
        }
    });
}
</script>

<style>
.badge_order {
    display: inline-block;
    padding: 2px 8px;
    background: #fff3e0;
    color: #e65100;
    border-radius: 3px;
    font-size: 0.92em;
    font-weight: 500;
}
</style>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
