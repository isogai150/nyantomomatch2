<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Post;
use App\Models\User;
use Mockery; // Stripeモック作成用
use Stripe\PaymentIntent;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 各テストの後にMockeryを閉じるためのメソッド
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     * 正常に決済Intentが作成されることを確認
     */
    public function process_payment_returns_client_secret_successfully()
    {
        // ================================
        // Arrange（準備）
        // ================================
        // ユーザーを作成し、認証状態にする
        $user = User::factory()->create();

        // 投稿データを作成（ユーザーに紐づけ）
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'cost' => 1000,
        ]);

        // Stripe\PaymentIntent::create() をモック（偽物）化する
        // ※ 実際のAPIを呼ばずに「成功レスポンスを返す」と定義
        $mockPaymentIntent = Mockery::mock('alias:' . PaymentIntent::class);
        $mockPaymentIntent
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'pi_test_123',
                'client_secret' => 'secret_abc123'
            ]);

        // ================================
        // Act（実行）
        // ================================
        // 認証済みユーザーとしてAPIエンドポイントを叩く
        $response = $this->actingAs($user)->postJson('/api/checkout/process', [
            'post_id' => $post->id,
            'email' => 'test@example.com',
            'user_id' => $user->id,
        ]);

        // ================================
        // Assert（検証）
        // ================================
        // ステータスコード200であること
        $response->assertStatus(200);

        // 返り値にclient_secretとpayment_intent_idが含まれていること
        $response->assertJsonStructure([
            'client_secret',
            'payment_intent_id',
        ]);
    }

    /**
     * @test
     * Stripe API呼び出し時に例外が発生した場合、500エラーを返すことを確認
     */
    public function process_payment_returns_error_when_stripe_fails()
    {
        // ================================
        // Arrange（準備）
        // ================================

        //  ユーザーを作成（外部キー整合性を保つ）
        $user = User::factory()->create();

        //  Postをそのユーザーに紐づけて作成
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'cost' => 1000,
        ]);

        //  Stripeのモック（エラーを発生させる）
        $mockPaymentIntent = Mockery::mock('alias:' . PaymentIntent::class);
        $mockPaymentIntent
            ->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Stripe API Error'));

        // ================================
        // Act（実行）
        // ================================
        $response = $this->actingAs($user)->postJson('/api/checkout/process', [
            'post_id' => $post->id,
            'email' => 'error@example.com',
            'user_id' => $user->id,
        ]);

        // ================================
        // Assert（検証）
        // ================================
        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Stripe API Error',
        ]);
    }
}
