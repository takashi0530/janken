<?php

const GUIDE_MESSAGE_FIRST = "first";
const GUIDE_MESSAGE_DRAW = "draw";
const GUIDE_MESSAGE_RETRY = "retry";
const RESULT_DRAW = 0;
const HAND_TYPE = [
	0 => "グー",
	1 => "チョキ",
	2 => "パー",
];
const WIN_OR_LOSE = [
	0 => "結果：あいこ",
	1 => "結果：あなたの負け",
	2 => "結果：あなたの勝ち",
];
const STOP = 0;
const RETRY = 1;
const RETRY_OR_STOP = [
	STOP => "stop",
	RETRY => "retry",
];

function jankenGame($type) {
	guideMessage($type);
	$com_hand = getComHand();										//コンピューターの手
	$your_hand = inputHand();										//プレイヤーの手
	echo "---------------------------------------" . PHP_EOL;
	echo "「最初はグー！じゃんけんぽん！」" . PHP_EOL;
	echo "あなたの手は： " . HAND_TYPE[$your_hand] . PHP_EOL;
	echo "コンピューターの手は： " . HAND_TYPE[$com_hand] . PHP_EOL;
	echo "---------------------------------------" . PHP_EOL;
	$result = judge($your_hand, $com_hand);   						//勝敗判定
	echo "====================" . PHP_EOL;
	echo WIN_OR_LOSE[$result] . PHP_EOL;							//結果表示
	echo "====================" . PHP_EOL;
	if ($result === RESULT_DRAW) {
		return jankenGame($type = GUIDE_MESSAGE_DRAW);							//あいこなら再帰関数
	}
	retryGame();		//もう一度ゲームをするか？
	$type = GUIDE_MESSAGE_FIRST;
	jankenGame($type);
	return;
}

function guideMessage($type) {
	if ($type === GUIDE_MESSAGE_FIRST) {
		echo "■---------------------------------------■" . PHP_EOL;
		echo "「じゃんけんスタート！」" . PHP_EOL;
		echo "「最初にあなたが出す手の数字を\n下から選んで正しく入力してください」" . PHP_EOL;
		echo "グー:0 or チョキ:1 or パー:2" . PHP_EOL;
		echo "■---------------------------------------■" . PHP_EOL;
	}
	if ($type === GUIDE_MESSAGE_DRAW) {
		echo "---------------------------------------" . PHP_EOL;
		echo "「あいこです！もう一度手を入力してください」" . PHP_EOL;
		echo "---------------------------------------" . PHP_EOL;
	}
	if ($type === GUIDE_MESSAGE_RETRY) {
		echo "---------------------------------------" . PHP_EOL;
		echo "「もう一度じゃんけんをしますか？」" . PHP_EOL;
		echo "0：やめる  or  1：続ける" . PHP_EOL;
		echo "---------------------------------------" . PHP_EOL;
	}
	return;
}

function getComHand() {
	$com_hand = array_rand(HAND_TYPE, 1);
	return $com_hand;
}

function inputHand() {
	$your_hand = trim(fgets(STDIN));
	$check = validationHands($your_hand);
	if ($check === false) {
		return inputHand();
	}
	return $your_hand;
}

function validationHands($your_hand) {
	if (!isset(HAND_TYPE[$your_hand])) {
		echo "---------------------------------------" . PHP_EOL;
		echo "「正しい手を入力してください」" . PHP_EOL;
		echo "---------------------------------------" . PHP_EOL;
		return false;
	}
	return true;
}

function judge($your_hand, $com_hand) {
	$judge = ($your_hand - $com_hand + 3) % 3;
	return $judge;
}

function retryGame() {
	guideMessage($type = GUIDE_MESSAGE_RETRY);
	$input = trim(fgets(STDIN));
	$check = validationRetry($input);
	if ($check === false) {
		return retryGame();
	}
	if ($input == STOP) {
		return gameEnd();
	}
	return;
}

function validationRetry($input) {
	if (!isset(RETRY_OR_STOP[$input])) {
		echo "---------------------------------------" . PHP_EOL;
		echo "「正しく入力してください」" . PHP_EOL;
		echo "---------------------------------------" . PHP_EOL;
		return false;
	}
	return true;
}

function gameEnd() {
	echo "---------------------------------------" . PHP_EOL;
	echo "「じゃんけんを終了します。」" . PHP_EOL;
	echo "---------------------------------------" . PHP_EOL;
	exit;
}

jankenGame($type = GUIDE_MESSAGE_FIRST);

