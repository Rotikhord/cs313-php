
CREATE TABLE employee (
emp_pk          SERIAL NOT NULL PRIMARY KEY,
emp_fname       VARCHAR(80) NOT NULL,
emp_lname       VARCHAR(80) NOT NULL,
emp_email       VARCHAR(80) NOT NULL UNIQUE,
emp_hash        VARCHAR(255) NOT NULL,
emp_permissions SMALLINT  NOT NULL
);

CREATE TABLE address (
add_pk          SERIAL NOT NULL PRIMARY KEY,
add_street      VARCHAR(100) NOT NULL,
add_city        VARCHAR(80) NOT NULL,
add_st          VARCHAR(2)  NOT NULL,
add_zip         VARCHAR(12) NOT NULL
);

CREATE TABLE customer (
cus_pk            SERIAL NOT NULL PRIMARY KEY,
cus_company_name  VARCHAR(80),
cus_contact_fname VARCHAR(80),
cus_contact_lname VARCHAR(80),
cus_email         VARCHAR(80),
cus_phone1        VARCHAR(14) NOT NULL,
cus_phone2        VARCHAR(14),
cus_fax           VARCHAR(14),
cus_add_fk        INT REFERENCES public.address(add_pk),
cus_active        BOOLEAN NOT NULL
);

CREATE TABLE job (
job_pk            SERIAL NOT NULL PRIMARY KEY,
job_number        VARCHAR(10) NOT NULL UNIQUE,
job_date          DATE NOT NULL,
job_description   TEXT NOT NULL,
job_cus_fk        INT NOT NULL REFERENCES public.customer(cus_pk),
job_add_fk        INT REFERENCES public.address(add_pk),
job_balance       REAL NOT NULL,
job_complete_date DATE
);

CREATE TABLE punchlog (
pun_pk            SERIAL NOT NULL PRIMARY KEY,
pun_date          DATE NOT NULL,
pun_description   TEXT NOT NULL,
pun_job_fk        INT NOT NULL REFERENCES public.job(job_pk),
pun_complete_date DATE
);

CREATE TABLE update (
upd_pk            SERIAL NOT NULL PRIMARY KEY,
upd_timestamp     TIMESTAMP NOT NULL,
upd_description   TEXT NOT NULL,
upd_payment       REAL,
upd_pun_fk        INT NOT NULL REFERENCES public.punchlog(pun_pk),
upd_emp_fk        INT NOT NULL REFERENCES public.employee(emp_pk)
);

CREATE TABLE assignment (
asi_pk            SERIAL NOT NULL PRIMARY KEY,
asi_pun_fk        INT NOT NULL REFERENCES public.punchlog(pun_pk),
asi_emp_fk        INT NOT NULL REFERENCES public.employee(emp_pk)
);
