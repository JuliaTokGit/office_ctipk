<?php
$clients = Client::select('id')->pluck('id')->all();
$deals_d = Deal::whereNotIn('client_id', $clients)->orWhereNull('client_id')->delete();
echo "Deals:";
print_r($deals_d);
echo "<br/>";
$contracts_d = Contract::whereNotIn('client_id', $clients)->orWhereNull('client_id')->delete();
echo "Contracts:";
print_r($contracts_d);
echo "<br/>";
$students_d = Student::whereNotIn('client_id', $clients)->orWhereNull('client_id')->delete();
echo "Students:";
print_r($students_d);
echo "<br/>";

$deals = Deal::select('id')->pluck('id')->all();
$orders_d = Order::whereNotIn('deal_id', $deals)->orWhereNull('deal_id')->delete();
echo "Orders:";
print_r($orders_d);
echo "<br/>";
$invoices_d = Invoice::whereNotIn('deal_id', $deals)->orWhereNull('deal_id')->delete();
echo "Invoices:";
print_r($invoices_d);
echo "<br/>";
$contract_applications_d = ContractApplication::whereNotIn('deal_id', $deals)->orWhereNull('deal_id')->delete();
echo "ContractApplications:";
print_r($contract_applications_d);
echo "<br/>";
$acts_d = Act::whereNotIn('deal_id', $deals)->orWhereNull('deal_id')->delete();
echo "Acts:";
print_r($acts_d);
echo "<br/>";

$students = Student::select('id')->pluck('id')->all();
$learning_d = Learning::whereNotIn('student_id', $students)->orWhereNull('student_id')->delete();
echo "Learnings:";
print_r($learning_d);
echo "<br/>";
$exams_d = Exam::whereNotIn('student_id', $students)->orWhereNull('student_id')->delete();
echo "Exams:";
print_r($exams_d);
echo "<br/>";

$orders = Order::select('id')->pluck('id')->all();
$learning_d = Learning::whereNotIn('order_id', $orders)->orWhereNull('order_id')->delete();
echo "Learnings:";
print_r($learning_d);
echo "<br/>";
$admits_d = AdmitOrder::whereNotIn('order_id', $orders)->orWhereNull('order_id')->delete();
echo "AdmitOrders:";
print_r($admits_d);
echo "<br/>";
$expel_d = ExpelOrder::whereNotIn('order_id', $orders)->orWhereNull('order_id')->delete();
echo "ExpelOrders:";
print_r($expel_d);
echo "<br/>";
$protocols_d = Protocol::whereNotIn('order_id', $orders)->orWhereNull('order_id')->delete();
echo "Protocols:";
print_r($protocols_d);
echo "<br/>";
$exams_d = Exam::whereNotIn('order_id', $orders)->orWhereNull('order_id')->delete();
echo "Exams:";
print_r($exams_d);
echo "<br/>";

$learnings = Learning::select('id')->pluck('id')->all();
$sertificates_d = Sertificate::whereNotIn('learning_id', $learnings)->orWhereNull('learning_id')->delete();
echo "Sertificates:";
print_r($sertificates_d);
echo "<br/>";


// $courses = Course::select('id')->pluck('id')->all();
// $questions_d = Question::whereNotIn('course_id', $courses)->orWhereNull('course_id')->delete();
// echo "Questions:";
// print_r($questions_d);
// echo "<br/>";
//
// $questions = Question::select('id')->pluck('id')->all();
// $answers_d = Answer::whereNotIn('question_id', $questions)->orWhereNull('question_id')->delete();
// echo "Answers:";
// print_r($answers_d);
// echo "<br/>";
exit();
