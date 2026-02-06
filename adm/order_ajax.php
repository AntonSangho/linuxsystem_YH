<?php
if (!defined('_GNUBOARD_')) exit;

include_once('./_common.php');

// 권한 체크
auth_check($auth[$sub_menu], "w");

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch($action) {
    case 'get':
        $od_id = sql_escape_string($_POST['od_id']);
        $sql = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'";
        $row = sql_fetch($sql);

        if ($row) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => '주문 데이터를 찾을 수 없습니다.']);
        }
        break;

    case 'delete':
        auth_check($auth[$sub_menu], "d");

        $od_id = sql_escape_string($_POST['od_id']);
        $sql = "DELETE FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'";
        sql_query($sql);

        // 관련 장바구니 데이터도 삭제
        $sql = "DELETE FROM {$g5['g5_shop_cart_table']} WHERE od_id = '{$od_id}'";
        sql_query($sql);

        echo json_encode(['success' => true, 'message' => '삭제되었습니다.']);
        break;

    case 'change_status':
        $od_id = sql_escape_string($_POST['od_id']);
        $od_status = sql_escape_string($_POST['od_status']);

        // 상태값 화이트리스트 검증
        $allowed_status = ['주문', '입금', '준비', '배송', '완료', '취소'];
        if (!in_array($od_status, $allowed_status)) {
            echo json_encode(['success' => false, 'message' => '잘못된 상태값입니다.']);
            break;
        }

        $sql = "UPDATE {$g5['g5_shop_order_table']}
                SET od_status = '{$od_status}'
                WHERE od_id = '{$od_id}'";
        sql_query($sql);

        echo json_encode(['success' => true, 'message' => '주문상태가 변경되었습니다.']);
        break;

    case 'delete_multiple':
        auth_check($auth[$sub_menu], "d");

        $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => '삭제할 항목이 없습니다.']);
            break;
        }

        $delete_count = 0;
        foreach ($ids as $od_id) {
            $od_id = sql_escape_string($od_id);

            $sql = "DELETE FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'";
            sql_query($sql);

            // 관련 장바구니 데이터도 삭제
            $sql = "DELETE FROM {$g5['g5_shop_cart_table']} WHERE od_id = '{$od_id}'";
            sql_query($sql);

            $delete_count++;
        }

        echo json_encode(['success' => true, 'message' => $delete_count.'건이 삭제되었습니다.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
}
?>
