<?php

declare(strict_types=1);

namespace Tests\CompanyPayrolls\Framework\Controller;

use DateTime;
use Doctrine\DBAL\Connection;
use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utils\TestDB;

class PayrollReportControllerTest extends WebTestCase
{
    protected KernelBrowser $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = self::createClient();
        $this->httpClient->getCookieJar()->clear();

        TestDB::$connection = $this->httpClient->getContainer()->get(Connection::class);
    }

    /**
     * @dataProvider getFilteredListDataProvider
     */
    public function testListIsCorrectFiltered(array $filters, string $reportDate, array $response): void
    {
        TestDB::assertRecordExists('payroll_reports', [
            'date' => '2020-01-31',
        ]);
        $this->httpClient->request(
            method: 'GET',
            uri: sprintf(
                '/api/companies/XYZ/payrolls/%s?filters[%s]=%s',
                $reportDate,
                key($filters),
                current($filters),
            )
        );
        self::assertEquals($response, json_decode($this->httpClient->getResponse()->getContent(), associative:  true));
    }

    private function getFilteredListDataProvider(): Generator
    {
        yield [
            [
                'departmentName' => 'HR',
            ],
            '2020-01-31',
            [
                'report' => [
                    [
                        'date' => '2020-01',
                        'employee_first_name' => 'Adam',
                        'employee_last_name' => 'Kowalski',
                        'department_name' => 'HR',
                        'base_salary_amount' => 100000,
                        'additional_to_base_salary_amount' => 100000,
                        'bonus_type' => 'FIXED',
                        'salary_with_bonus' => 200000,
                    ],
                ],
            ],
        ];
        yield [
            [
                'departmentName' => 'CR',
            ],
            '2020-01-31',
            [
                'report' => [
                    [
                        'date' => '2020-01',
                        'employee_first_name' => 'Ania',
                        'employee_last_name' => 'Nowak',
                        'department_name' => 'CR',
                        'base_salary_amount' => 110000,
                        'additional_to_base_salary_amount' => 11000,
                        'bonus_type' => 'PERCENTAGE',
                        'salary_with_bonus' => 121000,
                    ],
                ],
            ],
        ];
    }

    /** @dataProvider getSortedListDataProvider */
    public function testListIsCorrectSorted(
        string $orderField,
        string $orderedField,
        string|int $firstValue
    ): void {
        TestDB::assertRecordExists('payroll_reports', [
            'date' => '2020-01-31',
        ]);
        $this->httpClient->request(
            method: 'GET',
            uri: sprintf('/api/companies/XYZ/payrolls/2020-01-31?sort=%s', $orderField),
        );
        self::assertEquals(
            $firstValue,
            json_decode($this->httpClient->getResponse()->getContent(), associative: true)['report'][0][$orderedField]
        );
    }

    private function getSortedListDataProvider(): Generator
    {
        yield ['payrollReportDate', 'date', '2020-01'];
        yield ['-employeeFirstName', 'employee_first_name', 'Ania'];
        yield ['employeeFirstName', 'employee_first_name', 'Adam'];
        yield ['employeeLastName', 'employee_last_name', 'Kowalski'];
        yield ['-employeeLastName', 'employee_last_name', 'Nowak'];
        yield ['departmentName', 'department_name', 'CR'];
        yield ['-departmentName', 'department_name', 'HR'];
        yield ['baseSalaryAmount', 'base_salary_amount', 100000];
        yield ['-baseSalaryAmount', 'base_salary_amount', 110000];
        yield ['additionalToBaseSalaryAmount', 'additional_to_base_salary_amount', 11000];
        yield ['-additionalToBaseSalaryAmount', 'additional_to_base_salary_amount', 100000];
        yield ['bonusType', 'bonus_type', 'FIXED'];
        yield ['-bonusType', 'bonus_type', 'PERCENTAGE'];
        yield ['salaryWithBonus', 'salary_with_bonus', 121000];
        yield ['-salaryWithBonus', 'salary_with_bonus', 200000];
    }


    public function testItCreateCorrectPayrollReport(): void
    {
        $currentDate = new DateTime('last day of this month');

        TestDB::assertRecordMissing('payroll_reports', [
            'date' => $currentDate->format('Y-m-d'),
        ]);

        $this->httpClient->request(
            method: 'POST',
            uri: '/api/companies/XYZ/payrolls/generate_for_current_month'
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        TestDB::assertRecordExists(
            'payroll_reports',
            [
                'date' => $currentDate->format('Y-m-d'),
                'company_name' => 'XYZ',
            ]
        );
    }
}