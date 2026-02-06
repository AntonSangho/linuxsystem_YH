<?php
if (!defined('_GNUBOARD_')) exit;

$sub_menu = '400100';
include_once('./_common.php');

// 권한 체크
auth_check($auth[$sub_menu], "r");

$g5['title'] = '주문 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

// ============================================================
// 검색/필터 파라미터 처리
// ============================================================
$search_keyword = isset($_GET['search_keyword']) ? clean_xss_tags($_GET['search_keyword']) : '';
$search_field = isset($_GET['search_field']) ? clean_xss_tags($_GET['search_field']) : 'od_id';
$search_status = isset($_GET['search_status']) ? clean_xss_tags($_GET['search_status']) : '';
$search_date_from = isset($_GET['search_date_from']) ? clean_xss_tags($_GET['search_date_from']) : '';
$search_date_to = isset($_GET['search_date_to']) ? clean_xss_tags($_GET['search_date_to']) : '';

// 검색 필드 화이트리스트
$allowed_search_fields = ['od_id', 'od_name', 'mb_id'];
if (!in_array($search_field, $allowed_search_fields)) {
    $search_field = 'od_id';
}

// 정렬 파라미터 (화이트리스트 필수!)
$sst = isset($_GET['sst']) ? clean_xss_tags($_GET['sst']) : 'od_id';
$sod = isset($_GET['sod']) ? clean_xss_tags($_GET['sod']) : 'desc';

$allowed_sort_fields = ['od_id', 'od_name', 'od_receipt_price', 'od_status', 'od_time'];
if (!in_array($sst, $allowed_sort_fields)) {
    $sst = 'od_id';
}
if ($sod != 'asc' && $sod != 'desc') {
    $sod = 'desc';
}

// 페이지 번호
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// ============================================================
// WHERE 조건 구성
// ============================================================
$where = " WHERE 1=1 ";

if ($search_keyword) {
    $where .= " AND {$search_field} LIKE '%".sql_escape_string($search_keyword)."%' ";
}

if ($search_status) {
    $where .= " AND od_status = '".sql_escape_string($search_status)."' ";
}

if ($search_date_from && $search_date_to) {
    $where .= " AND DATE(od_time) BETWEEN '".sql_escape_string($search_date_from)."'
                AND '".sql_escape_string($search_date_to)."' ";
} elseif ($search_date_from) {
    $where .= " AND DATE(od_time) >= '".sql_escape_string($search_date_from)."' ";
} elseif ($search_date_to) {
    $where .= " AND DATE(od_time) <= '".sql_escape_string($search_date_to)."' ";
}

// ============================================================
// 통계 조회
// ============================================================
$sql = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} {$where}";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 상태별 통계
$sql_order = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '주문'";
$order_count = sql_fetch($sql_order)['cnt'];

$sql_deposit = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '입금'";
$deposit_count = sql_fetch($sql_deposit)['cnt'];

$sql_prepare = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '준비'";
$prepare_count = sql_fetch($sql_prepare)['cnt'];

$sql_delivery = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '배송'";
$delivery_count = sql_fetch($sql_delivery)['cnt'];

$sql_complete = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '완료'";
$complete_count = sql_fetch($sql_complete)['cnt'];

$sql_cancel = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_order_table']} WHERE od_status = '취소'";
$cancel_count = sql_fetch($sql_cancel)['cnt'];

// ============================================================
// 페이징 계산
// ============================================================
$rows = 20;
$total_page = ceil($total_count / $rows);
if ($page > $total_page && $total_page > 0) $page = $total_page;
$from_record = ($page - 1) * $rows;

// ============================================================
// 목록 조회
// ============================================================
$sql = "SELECT * FROM {$g5['g5_shop_order_table']} {$where}
        ORDER BY {$sst} {$sod}
        LIMIT {$from_record}, {$rows}";
$result = sql_query($sql);

// 정렬 링크용 쿼리스트링
$qstr = '';
if ($search_keyword) $qstr .= '&search_keyword='.urlencode($search_keyword);
if ($search_field) $qstr .= '&search_field='.$search_field;
if ($search_status) $qstr .= '&search_status='.urlencode($search_status);
if ($search_date_from) $qstr .= '&search_date_from='.$search_date_from;
if ($search_date_to) $qstr .= '&search_date_to='.$search_date_to;
?>

<!-- ============================================================ -->
<!-- 페이지 설명 -->
<!-- ============================================================ -->
<div class="local_desc01">
    <p><strong>주문 내역을 관리합니다.</strong></p>
    <ul>
        <li>주문 상태 변경, 검색, 일괄 삭제가 가능합니다.</li>
        <li>주문번호, 주문자명, 회원ID로 검색할 수 있습니다.</li>
    </ul>
</div>

<!-- ============================================================ -->
<!-- 통계 영역 -->
<!-- ============================================================ -->
<div class="local_ov01 local_ov">
    <span class="btn_ov01">
        <span class="ov_txt">전체</span>
        <span class="ov_num"><?php echo number_format($total_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">주문</span>
        <span class="ov_num"><?php echo number_format($order_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">입금</span>
        <span class="ov_num"><?php echo number_format($deposit_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">준비</span>
        <span class="ov_num"><?php echo number_format($prepare_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">배송</span>
        <span class="ov_num"><?php echo number_format($delivery_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">완료</span>
        <span class="ov_num"><?php echo number_format($complete_count); ?>건</span>
    </span>
    <span class="btn_ov01">
        <span class="ov_txt">취소</span>
        <span class="ov_num"><?php echo number_format($cancel_count); ?>건</span>
    </span>
</div>

<!-- ============================================================ -->
<!-- 필터/검색 섹션 -->
<!-- ============================================================ -->
<div class="unified_filter_section">
    <form name="fsearch" method="get">
        <div class="filter_row">
            <span class="filter_label">주문상태</span>
            <select name="search_status">
                <option value="">전체</option>
                <option value="주문"<?php echo $search_status=='주문'?' selected':''; ?>>주문</option>
                <option value="입금"<?php echo $search_status=='입금'?' selected':''; ?>>입금</option>
                <option value="준비"<?php echo $search_status=='준비'?' selected':''; ?>>준비</option>
                <option value="배송"<?php echo $search_status=='배송'?' selected':''; ?>>배송</option>
                <option value="완료"<?php echo $search_status=='완료'?' selected':''; ?>>완료</option>
                <option value="취소"<?php echo $search_status=='취소'?' selected':''; ?>>취소</option>
            </select>

            <span class="filter_label" style="margin-left:20px;">기간</span>
            <input type="text" name="search_date_from" id="search_date_from"
                   class="frm_input" size="12" placeholder="시작일" readonly
                   value="<?php echo htmlspecialchars($search_date_from); ?>">
            <span>~</span>
            <input type="text" name="search_date_to" id="search_date_to"
                   class="frm_input" size="12" placeholder="종료일" readonly
                   value="<?php echo htmlspecialchars($search_date_to); ?>">

            <button type="button" class="btn btn_date_preset" data-days="0">오늘</button>
            <button type="button" class="btn btn_date_preset" data-days="7">7일</button>
            <button type="button" class="btn btn_date_preset" data-days="30">30일</button>
        </div>
        <div class="filter_row">
            <span class="filter_label">검색</span>
            <select name="search_field">
                <option value="od_id"<?php echo $search_field=='od_id'?' selected':''; ?>>주문번호</option>
                <option value="od_name"<?php echo $search_field=='od_name'?' selected':''; ?>>주문자명</option>
                <option value="mb_id"<?php echo $search_field=='mb_id'?' selected':''; ?>>회원ID</option>
            </select>
            <input type="text" name="search_keyword" class="frm_input" style="width:200px;"
                   placeholder="검색어" value="<?php echo htmlspecialchars($search_keyword); ?>">

            <button type="submit" class="btn btn_01">검색</button>
            <button type="button" class="btn btn_02" onclick="location.href='?'">초기화</button>
        </div>
    </form>
</div>

<!-- ============================================================ -->
<!-- 액션 버튼 -->
<!-- ============================================================ -->
<div class="btn_fixed_top">
    <button type="button" class="btn btn_02" onclick="deleteSelected()">선택 삭제</button>
</div>

<!-- ============================================================ -->
<!-- 데이터 테이블 -->
<!-- ============================================================ -->
<form name="flist" id="flist" method="post">
<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
        <tr>
            <th scope="col" class="td_chk">
                <input type="checkbox" id="chkall" onclick="check_all(this.form)">
            </th>
            <th scope="col" class="td_num"><?php echo get_sort_link('od_id', '주문번호', $sst, $sod, $qstr); ?></th>
            <th scope="col"><?php echo get_sort_link('od_name', '주문자', $sst, $sod, $qstr); ?></th>
            <th scope="col">상품명</th>
            <th scope="col" class="td_num"><?php echo get_sort_link('od_receipt_price', '결제금액', $sst, $sod, $qstr); ?></th>
            <th scope="col" class="td_stat"><?php echo get_sort_link('od_status', '상태', $sst, $sod, $qstr); ?></th>
            <th scope="col" class="td_datetime"><?php echo get_sort_link('od_time', '주문일', $sst, $sod, $qstr); ?></th>
            <th scope="col" class="td_mng">관리</th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);

        // 주문에 포함된 첫 번째 상품명 조회
        $sql_item = "SELECT it_name FROM {$g5['g5_shop_cart_table']}
                     WHERE od_id = '".sql_escape_string($row['od_id'])."'
                     ORDER BY ct_id ASC LIMIT 1";
        $item_row = sql_fetch($sql_item);
        $item_name = $item_row['it_name'] ? $item_row['it_name'] : '-';

        // 해당 주문의 상품 수
        $sql_item_cnt = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_cart_table']}
                         WHERE od_id = '".sql_escape_string($row['od_id'])."'";
        $item_cnt = sql_fetch($sql_item_cnt)['cnt'];
        if ($item_cnt > 1) {
            $item_name .= ' 외 '.($item_cnt - 1).'건';
        }
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo htmlspecialchars($row['od_id']); ?>">
        </td>
        <td class="td_num"><?php echo htmlspecialchars($row['od_id']); ?></td>
        <td><?php echo htmlspecialchars($row['od_name']); ?></td>
        <td class="td_left"><?php echo htmlspecialchars($item_name); ?></td>
        <td class="td_num"><?php echo number_format($row['od_receipt_price']); ?>원</td>
        <td class="td_stat">
            <?php
            switch($row['od_status']) {
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
                    echo htmlspecialchars($row['od_status']);
            }
            ?>
        </td>
        <td class="td_datetime"><?php echo substr($row['od_time'], 0, 16); ?></td>
        <td class="td_mng">
            <a href="./order_detail.php?od_id=<?php echo urlencode($row['od_id']); ?>" class="btn btn_03">상세</a>
        </td>
    </tr>
    <?php
    }
    if ($i == 0) {
        echo '<tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>
</form>

<!-- ============================================================ -->
<!-- 하단 일괄 처리 -->
<!-- ============================================================ -->
<div class="btn_fixed_top" style="margin-top:10px;">
    <button type="button" class="btn btn_02" onclick="deleteSelected()">선택 삭제</button>
</div>

<!-- ============================================================ -->
<!-- 페이징 -->
<!-- ============================================================ -->
<?php
$paging_url = '?'.$qstr.'&page=';
echo get_paging($rows, $page, $total_page, $paging_url);
?>

<!-- ============================================================ -->
<!-- JavaScript -->
<!-- ============================================================ -->
<?php include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php'); ?>
<script>
$(function(){
    // Datepicker 초기화
    $("#search_date_from, #search_date_to").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true
    });

    // 날짜 프리셋 버튼
    $('.btn_date_preset').click(function() {
        var days = parseInt($(this).data('days'));
        var today = new Date();
        var fromDate = new Date();

        if (days > 0) {
            fromDate.setDate(today.getDate() - days);
        }

        var formatDate = function(date) {
            var y = date.getFullYear();
            var m = ('0' + (date.getMonth() + 1)).slice(-2);
            var d = ('0' + date.getDate()).slice(-2);
            return y + '-' + m + '-' + d;
        };

        $('#search_date_from').val(formatDate(fromDate));
        $('#search_date_to').val(formatDate(today));
    });
});

// 선택 삭제
function deleteSelected() {
    var checked = $('input[name="chk[]"]:checked');
    if (checked.length === 0) {
        alert('삭제할 주문을 선택하세요.');
        return;
    }

    if (!confirm('선택한 ' + checked.length + '건의 주문을 삭제하시겠습니까?\n삭제된 주문은 복구할 수 없습니다.')) return;

    var ids = [];
    checked.each(function() {
        ids.push($(this).val());
    });

    $.ajax({
        url: './order_ajax.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'delete_multiple', ids: ids },
        success: function(res) {
            alert(res.message);
            if (res.success) {
                location.reload();
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
