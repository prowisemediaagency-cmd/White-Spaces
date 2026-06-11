# Informa WhiteSpace Radar — análise de portfólio BR × MX

Sistema Laravel 12 para mapear cobertura setorial do portfólio de eventos, identificar
white spaces e sinergias entre Brasil e México, e produzir recomendações go/no-go
auditáveis (score ponderado + justificativa + âncora sugerida).

## Stack e decisões

- **Laravel 12 + Blade puro + SQLite** — zero build de front (sem Node/Vite) e zero
  config de banco. Roda com PHP 8.2+ e Composer, nada mais.
- **CSS artesanal** (`public/css/app.css`) — design neo-brutalista com tokens da marca.
  Os hex do Informa Pink e das divisões são **aproximações**: troque pelas cores oficiais
  do brand book nas variáveis `:root` (uma linha por cor).
- **Sem autenticação na v1** — é deliberado. Para adicionar login:
  `composer require laravel/breeze && php artisan breeze:install blade`.

## Como rodar

```bash
cd whitespace-radar
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Abra http://localhost:8000.

## Módulos

| Rota | O que faz |
|---|---|
| `/` | Dashboard: KPIs, white spaces BR/MX, cobertura por setor, top recomendações |
| `/events` | CRUD completo da base de eventos, com filtros e busca |
| `/sectors` | Taxonomia setorial (criar/excluir) com leitura de white space |
| `/matrix` | Matriz decisória: notas 1–5 por setor/país, pesos e limiares editáveis |
| `/opportunities` | Recomendações carimbadas GO / REVIEW / NO-GO com score por extenso |

## Como o motor decide (`app/Services/PortfolioAnalysisService.php`)

**Score (0–100)** = média ponderada de: potencial de mercado, concorrência *invertida*
(6 − intensidade), acesso a público e força interna. Pesos padrão 35/25/20/20 e
limiares GO ≥ 70, REVIEW ≥ 45 — tudo editável em `/matrix`.

**Tipo de jogada**, derivado da cobertura real da base:

1. Sem evento local + irmão LATAM cobre → **geo-clone** (replicar marca da casa)
2. Sem evento local + marca global cobre → **importar marca global**
3. Sem evento em lugar nenhum → **novo evento** (greenfield/aquisição)
4. Coberto local + marca externa forte ausente → **co-location**
5. Coberto local com cluster já consolidado (mesmo venue, datas sobrepostas) → **ajuste de portfólio**

## Limitações que importam (leia antes do comitê)

1. **As avaliações seedadas são baseline, não pesquisa.** Estão marcadas com
   `[Baseline do seed — validar]`. O sistema estrutura julgamento; não o substitui.
2. **A base fornecida chegou truncada e com 1 evento no México.** Enquanto o portfólio
   MX real não for carregado via CRUD, os white spaces MX medem ausência de cadastro,
   não ausência de mercado. O dashboard exibe esse alerta automaticamente.
3. **Concorrência é nota manual (1–5).** Evolução natural: cadastrar eventos de
   concorrentes na própria base (flag `is_competitor`) e derivar a nota do calendário
   real em vez do julgamento.
4. **Sem testes automatizados nem auth** — adicioná-los antes de uso multiusuário.

## Correções aplicadas à base original no seed

País definido pelo venue: Abastur → MX; Toronto Boat Show → CA; Global Medtech
Connect → IN; Transcontinental Trusts → BM; Energy Storage Summit LATAM → CL (estava
duplicado em BR e CL); Edmonton Expo deduplicado; "AeroEngines Americas (2027)"
descartado porque a fonte chegou cortada nessa linha.
