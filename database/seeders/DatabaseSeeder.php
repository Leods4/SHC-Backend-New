<?php

namespace Database\Seeders;

use App\Enums\TipoUsuario;
use App\Models\Configuracao;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Cursos
        // Mantemos o ADS em uma variável separada para vincular os alunos de teste a ele
        $cursoAds = Curso::create([
            'nome' => 'Análise e Desenvolvimento de Sistemas',
            'horas_necessarias' => 200
        ]);

        $outrosCursos = [
            'Pedagogia',
            'Administração',
            'Processos Gerenciais'
        ];

        foreach ($outrosCursos as $nomeCurso) {
            Curso::create([
                'nome' => $nomeCurso,
                'horas_necessarias' => 200 // Definindo padrão de 200h, ajuste conforme necessário
            ]);
        }

        // 2. Admin
        User::create([
            'nome'         => 'Administrador Principal',
            'email'        => 'admin@fmp.edu.br',
            'cpf'          => '000.000.000-00',
            'data_nascimento' => '1990-01-01',
            'password'     => Hash::make('admin123'),
            'tipo'         => TipoUsuario::ADMINISTRADOR,
        ]);

        // 3. Secretaria
        User::create([
            'nome'         => 'Responsável Secretaria',
            'email'        => 'secretaria@fmp.edu.br',
            'cpf'          => '111.111.111-11',
            'data_nascimento' => '1992-05-10',
            'password'     => Hash::make('sec123'),
            'tipo'         => TipoUsuario::SECRETARIA,
        ]);

        // 4. Coordenador (Vinculado a ADS)
        User::create([
            'nome'         => 'Prof. Coordenador ADS',
            'email'        => 'coord.ads@fmp.edu.br',
            'cpf'          => '222.222.222-22',
            'data_nascimento' => '1985-08-20',
            'password'     => Hash::make('coord123'),
            'tipo'         => TipoUsuario::COORDENADOR,
            'curso_id'     => $cursoAds->id,
        ]);

        // 5. Aluno com senha própria
        User::create([
            'nome'         => 'João da Silva (Teste)',
            'email'        => 'aluno@fmp.edu.br',
            'cpf'          => '333.333.333-33',
            'data_nascimento' => '2003-04-15',
            'matricula'    => '20250001',
            'password'     => Hash::make('aluno123'),
            'tipo'         => TipoUsuario::ALUNO,
            'avatar_url'   => 'https://ui-avatars.com/api/?name=Joao+Silva',
            'curso_id'     => $cursoAds->id,
            'fase'         => 3,
        ]);

        // 5b. Aluno sem senha → cria senha padrão ddmmaaaa
        $data = Carbon::parse('2004-02-13')->format('dmY'); // "13022004"

        User::create([
            'nome'            => 'Maria Oliveira (Sem Senha)',
            'email'           => 'aluno2@fmp.edu.br',
            'cpf'             => '444.444.444-44',
            'data_nascimento' => '2004-02-13',
            'matricula'       => '20250002',
            'password'        => Hash::make($data),
            'tipo'            => TipoUsuario::ALUNO,
            'curso_id'        => $cursoAds->id,
            'fase'            => 1,
        ]);

        // 6. Configurações iniciais
        Configuracao::create(['chave' => 'modo_manutencao', 'valor' => 'false']);
        Configuracao::create(['chave' => 'total_horas_padrao', 'valor' => '200']);

        // 7. Categorias (Atualizadas)
        $categorias = [
            'Científico/Acadêmicas',
            'Sócio-Culturais',
            'Prática Profissional'
        ];

        foreach ($categorias as $cat) {
            \App\Models\Categoria::create(['nome' => $cat]);
        }
    }
}
