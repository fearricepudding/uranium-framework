<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require(__DIR__."/../app/uranium/Model.php");
require(__DIR__."/../models/UserModel.php");

use uranium\core\Model;
use uranium\model\UserModel;

final class ModelTest extends TestCase{
    public function testModelCanBeCreated(): void {
        $model = new Model();
        $this->assertInstanceOf(Model::class, $model);
    }

    public function testModelWhere(): void{
        $model = new UserModel();
        $model->where("something", "else");
        $expected = Array();
        $sel1 = ["key" => "something", "value" => "else"];
        $expected[] = $sel1;
        $this->assertSame($expected, $model->getSelectors());
    }

    public function testModelWhereAnd(): void{
        $model = new UserModel();
        $expected = Array();
        $sel1 = ["key" => "something", "value" => "value"];
        $sel2 = ["key" => "else", "value" => "anotherValue"];
        $expected[] = $sel1;
        $expected[] = $sel2;
        $whereAndTest = $model->whereAnd(["something"=>"value", "else"=>"anotherValue"]);
        $this->assertSame($expected, $whereAndTest->getSelectors());
    }

    public function testModelGet(): void{
        $model1 = new UserModel();
        $model1->test = true;
        $sql1 = $model1->whereAnd(["username"=>"test", "email"=>"anotherValue"])->get();
        $expected = "SELECT `id`, `username`, `active` FROM User WHERE `username`=:username  AND `email`=:email ";
        $this->assertSame($expected, $sql1);

        $model2 = new UserModel();
        $model2->test = true;
        $sql2 = $model2->withProtected()->get();
        $expected = "SELECT `id`, `username`, `email`, `password`, `active` FROM User";
        $this->assertSame($expected, $sql2);
    }
}
