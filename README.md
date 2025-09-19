# Sistema de Avaliação SCORDON

Sistema interno para coleta e análise de avaliações de atendimento ao cliente.

## 📋 Sobre o Projeto

Sistema desenvolvido para a **SCORDON** (Sistemas desde 1993) para coletar feedback dos clientes sobre o atendimento prestado. O sistema permite que funcionários gerem links únicos de avaliação e acompanhem os resultados através de dashboard e relatórios.

## 🚀 Funcionalidades

### 🔐 Sistema de Autenticação
- **3 níveis de usuário:** Admin, Suporte, Atendente
- **Controle de permissões** por role
- **Tela de login** estilizada
- **Logout seguro** com invalidação de sessão

### 🔗 Geração de Links
- **Links únicos** de avaliação por token
- **Seleção de empresa** com autocomplete
- **Atendente pré-selecionado** (usuário logado)
- **Interface intuitiva** para funcionários

### ⭐ Formulário de Avaliação
- **Avaliação por estrelas** (1 a 5)
- **Comentários opcionais**
- **Dados do cliente** (nome e email opcionais)
- **Proteção anti-duplicação**

### 📊 Dashboard e Relatórios
- **Estatísticas em tempo real**
- **Gráficos interativos** (Chart.js)
- **Filtros por empresa, atendente e data**
- **Exportação CSV** dos dados
- **Paginação estilizada**

### 👥 Gestão de Usuários (Admin)
- **Cadastro de usuários** com seleção de perfil
- **Edição** de dados e permissões
- **Controle de status** (ativo/inativo)
- **Interface administrativa**

## 🛠️ Tecnologias Utilizadas

- **Backend:** Laravel 11
- **Frontend:** Tailwind CSS + JavaScript (jQuery)
- **Banco:** MySQL/MariaDB
- **Gráficos:** Chart.js
- **Ícones:** Font Awesome
- **Autenticação:** Laravel Auth customizado

## 📁 Estrutura do Banco

### Tabelas Principais
- **`usuarios`** - Funcionários SCORDON (admin, suporte, atendente)
- **`empresas`** - Empresas clientes da SCORDON
- **`avaliacoes`** - Avaliações coletadas

### Relacionamentos
- Usuários podem atender qualquer empresa
- Avaliações vinculam empresa + usuário + feedback

## 🎨 Design

### Paleta de Cores SCORDON
- **Amarelo Principal:** #FBBF24
- **Amarelo Claro:** #FCD34D
- **Amarelo Suave:** #FDE68A
- **Fundo:** #FFFBEB

### Identidade Visual
- **Logo SCORDON** em destaque
- **Design moderno** e responsivo
- **Consistência** em todas as páginas
- **UX otimizada** para uso interno

## 🔑 Usuários Padrão

| Email | Senha | Perfil | Acesso |
|-------|-------|---------|---------|
| admin@scordon.com.br | 123456 | Admin | Total |
| suporte@scordon.com.br | 123456 | Suporte | Dashboard + Relatórios |
| joao@scordon.com.br | 123456 | Atendente | Gerar Links |

## 🚀 Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/LarissaMFeltrin/ScAvaliacaoSup.git
cd ScAvaliacaoSup
```

2. **Instale dependências**
```bash
composer install
```

3. **Configure ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure banco de dados**
- Edite `.env` com dados do banco
- Execute migrations:
```bash
php artisan migrate
```

5. **Popule dados iniciais**
```bash
php artisan db:seed --class=LimparBancoSeeder
php artisan db:seed --class=ImportarEmpresasSeeder
```

6. **Inicie servidor**
```bash
php artisan serve
```

## 📞 Suporte

**SCORDON - Sistemas desde 1993**
- **Telefone:** (44) 3028-4949
- **WhatsApp:** (44) 98811-7771
- **Email:** suporte@scordon.com.br

---

© 2025 Sistemas SCORDON. Todos os direitos reservados.