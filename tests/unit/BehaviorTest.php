<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 30.04.15
 * Time: 1:25
 */

class BehaviorTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    public static function setUpBeforeClass()
    {
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
            static::markTestSkipped('PDO and SQLite extensions are required.');
        }
    }

    private function loginUser($id){
        return Yii::$app->user->login(data\User::findIdentity($id));
    }

    public function setUp()
    {
        $this->mockApplication(require(Yii::getAlias($this->appConfig)));

        if(Yii::$app->user->isGuest)
            $this->loginUser(100);
    }

    public function tearDown()
    {
        //data\Post::deleteAll();
        parent::tearDown();
    }

    public function testCreatePostByAdmin()
    {
        $post = new data\Post();

        //add test data
        $post->content = "test content";
        $post->save();

        $this->assertTrue($post->created_by==100&&$post->updated_by==100);
    }

    /**
     *  @depends testCreatePostByAdmin
     */
    public function testChangePostByDemo(){
        $post = data\Post::find()->one();

        $this->loginUser(101);
        $post->content = "test content change";
        $post->save();

        $this->assertTrue($post->created_by==100&&$post->updated_by==101);
    }
} 