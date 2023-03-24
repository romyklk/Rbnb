<?php

namespace App\Test\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BookingRepository $repository;
    private string $path = '/booking/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Booking::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Booking index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'booking[startDate]' => 'Testing',
            'booking[createdAt]' => 'Testing',
            'booking[amount]' => 'Testing',
            'booking[reservationDate]' => 'Testing',
            'booking[booker]' => 'Testing',
            'booking[ad]' => 'Testing',
        ]);

        self::assertResponseRedirects('/booking/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Booking();
        $fixture->setStartDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setAmount('My Title');
        $fixture->setReservationDate('My Title');
        $fixture->setBooker('My Title');
        $fixture->setAd('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Booking');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Booking();
        $fixture->setStartDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setAmount('My Title');
        $fixture->setReservationDate('My Title');
        $fixture->setBooker('My Title');
        $fixture->setAd('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'booking[startDate]' => 'Something New',
            'booking[createdAt]' => 'Something New',
            'booking[amount]' => 'Something New',
            'booking[reservationDate]' => 'Something New',
            'booking[booker]' => 'Something New',
            'booking[ad]' => 'Something New',
        ]);

        self::assertResponseRedirects('/booking/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getStartDate());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getAmount());
        self::assertSame('Something New', $fixture[0]->getReservationDate());
        self::assertSame('Something New', $fixture[0]->getBooker());
        self::assertSame('Something New', $fixture[0]->getAd());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Booking();
        $fixture->setStartDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setAmount('My Title');
        $fixture->setReservationDate('My Title');
        $fixture->setBooker('My Title');
        $fixture->setAd('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/booking/');
    }
}
