## Company Payroll API

Additional indexes were not added to the report tables
because existing queries can simply scan results filtered by payroll_reports_uuid.
In case when some company will have a lot of employees,
we can consider adding indexes for the most requested fields, such as department name.
N+1 problem omitted.
### Installation host

To set up the project on host:

```bash
make up
```

### Conainer command (in php container via make)

#### Initialize Test Database
```bash
init-test-db:
  bin/console --env=test doctrine:database:drop --force --if-exists 
  bin/console --env=test doctrine:database:create
  bin/console --env=test doctrine:migration:migrate --no-interaction
  bin/console --env=test doctrine:fixtures:load --no-interaction
```
#### Run PHPUnit
```bash
test:
  bin/console --env=test c:c
  bin/phpunit
```
#### Run static analysis
```bash
analyze:
  vendor/bin/phpstan analyse src --level 9
  vendor/bin/ecs
```

### Endpoints Example usage

- **GET** `http://localhost:8080/api/companies/{companyName}/payrolls/{date}`:
  - Retrieve a list of payroll reports for the specified company and date.

- **GET** `http://localhost:8080/api/companies/{companyName}/payrolls/{date}?filters[departmentName]=HR&filters[employeeFirstName]=Ania&sort=payrollReportDate`:
  - Filter by department name and employeeFirstName.

- **POST** `http://localhost:8080/api/companies/{companyName}/payrolls/generate_for_current_month`:
  - Create payroll reports for the current month if they do not exist.

### Usage

To use the API, make sure the containers are up and running. Then, you can access the endpoints mentioned above using `http://localhost:8080`.
