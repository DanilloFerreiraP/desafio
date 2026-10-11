
-- 1.Listar os pacientes com seus nomes e o nome da respectiva unidade de saude(JOIN).

SELECT
    p.name AS patient_name,
    h.name AS health_unit_name
FROM patients AS p
INNER JOIN health_units AS h
    ON h.id = p.health_unit_id;


-- 2. Contar quantos pacientes existem em cada unidade, incluindo unidades sem pacientes (GROUP BY e LEFT JOIN).

SELECT
    h.id,
    h.name AS health_unit_name,
    COUNT(p.id) AS total_patients
FROM health_units AS h
LEFT JOIN patients AS p
    ON p.health_unit_id = h.id
GROUP BY h.id, h.name;


-- 3. Listar os pacientes cujo nome contenha um termo informado, como “Maria”(LIKE).

SELECT 
    id,
    `name` 
FROM patients
WHERE `name` LIKE '%Maria%';
