<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\TranslatorInterface;

class ScoringControllerTest extends WebTestCase
{
    public function testHomeResponse(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Скоринг');
        $this->assertSelectorTextContains('a', 'Регистрация клиента');
    }

    public function testListResponse(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Управление клиентами');
        $this->assertSelectorTextContains('a', 'Регистрация клиента');
    }

    public function testRegisterResponse(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Регистрация клиента');
        $this->assertSelectorTextContains('a', 'Регистрация клиента');
    }

    public function testEditResponse(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit/1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Редактирование карточки клиента');
        $this->assertSelectorTextContains('a', 'Регистрация клиента');
    }
}
