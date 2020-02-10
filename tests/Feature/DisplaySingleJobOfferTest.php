<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Entity\Tag;
use App\Tests\TestCase;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class DisplaySingleJobOfferTest extends TestCase
{
    private $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = $this->factory->create(Company::class, [
            'name' => 'Dunder Mifflin',
            'logo' => 'https://imageurl.com/image.png',
            'address' => 'Wall st, 11',
            'email' => 'contact@dm.com',
            'password' => '12345',
            'phoneNumber' => '9999-0000',
        ]);
    }

    public function test_display_a_single_job_offer(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Site Reliability Engineering Manager',
            'description' => 'You will support engineers developing services and infrastructure.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 4000,
            'maximumSalary' => 4500,
            'status' => JobOffer::STATUS_APPROVED,
            'hiringType' => JobOffer::HIRING_TYPE_CLT,
            'publishedAt' => new DateTime('2019-01-01'),
            'allowRemote' => true,
        ]);

        $this->client->request('GET', "/api/job-offers/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_displaying_non_approved_job_offers_causes_404(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Database Reliability Engineer',
            'description' => 'Our Database Reliability Engineering (DRE) team supports Yelp’s database infrastructure',
            'company' => $this->company,
            'seniorityLevel' => 'Mid-Senior',
            'minimumSalary' => 3000,
            'maximumSalary' => 3200,
            'status' => JobOffer::STATUS_PENDING_REVIEW,
            'hiringType' => JobOffer::HIRING_TYPE_PJ,
        ]);

        $this->client->request('GET', "/api/job-offers/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function test_display_by_slug_a_single_job_offer(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Super Master Engineering Manager',
            'description' => 'You will be a development Jedy.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 1121,
            'maximumSalary' => 2128,
            'publishedAt' => new DateTime('2019-01-01'),
            'status' => JobOffer::STATUS_APPROVED,
            'hiringType' => JobOffer::HIRING_TYPE_CLT,
            'allowRemote' => false,
        ]);

        $this->client->request('GET', "/api/job-offers/slug/{$jobOffer->getSlug()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_display_a_single_job_offer_with_one_tag(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Super Master Engineering Manager',
            'description' => 'You will be a development Jedy.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 1121,
            'maximumSalary' => 2128,
            'status' => JobOffer::STATUS_APPROVED,
            'publishedAt' => new DateTime('2019-01-01'),
            'allowRemote' => false,
        ]);

        $tag = $this->factory->create(Tag::class, [
            'name' => 'php',
        ]);

        $jobOffer->addTag($tag);

        $this->client->request('GET', "/api/job-offers/slug/{$jobOffer->getSlug()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }
}
