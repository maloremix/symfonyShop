<?php

namespace App\Tests\Controller;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testUnauthorizedUserRedirectedToErrorPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/order/create');

        $this->assertResponseRedirects('/error');
    }

    public function testAuthorizedUserSeesOrderForm(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/order/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="order"]');

        $this->assertSelectorExists('input[name="order[email]"]');
        $this->assertSelectorExists('select[name="order[service]"]');
        $this->assertSelectorExists('input[name="order[price]"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testErrorRedirectedForIncompleteForm(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@example.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/order/create');

        $form = $crawler->filter('form[name="order"]')->form();

        $form['order[email]'] = 'test@example.com';

        $client->submit($form);

        $this->assertResponseRedirects('/error');

        $form['order[email]'] = null;
        $form['order[service]'] = '1';

        $client->submit($form);

        $this->assertResponseRedirects('/error');
    }


    public function testOrderCreatedAfterFormSubmission(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@example.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/order/create');
        $form = $crawler->filter('form[name="order"]')->form();

        $form['order[email]'] = 'test@example.com';
        $form['order[service]'] = '1';

        $client->submit($form);

        $this->assertResponseRedirects('/order/create');

        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $newOrder = $orderRepository->findLastByEmailAndServiceId('test@example.com', 1);
        $this->assertNotNull($newOrder);
    }
}
