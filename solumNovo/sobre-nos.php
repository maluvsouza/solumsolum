<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_set_cookie_params([
    'lifetime' => 0,                
    'path' => '/',
    'domain' => 'solum.hubsapiens.com.br',
    'secure' => true,              
    'httponly' => true,           
    'samesite' => 'Lax'           
]);

session_start();

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {
    
    require_once( "./utils/navbar_logado.php");
} else {
   
    require_once( "./utils/navbar_nao-logado.php");
}
?>
    <section class="mission-section">
        <div class="container-sobre">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>Nossa Missão</h2>
                    <p>O Solum nasceu da necessidade de criar uma ponte entre consumidores conscientes e produtos verdadeiramente sustentáveis. Acreditamos que pequenas escolhas diárias podem gerar grandes transformações ambientais.</p>
                    <p>Nosso objetivo é democratizar o acesso a produtos ecológicos, facilitando a vida de quem quer fazer escolhas mais sustentáveis sem abrir mão da qualidade e praticidade.</p>
                    
                    <div class="mission-stats">
                        <div class="mission-stat">
                            <div class="stat-icon">
                                <i class="fas fa-recycle"></i>
                            </div>
                            <div>
                                <h3>100% Sustentável</h3>
                                <p>Todos os produtos passam por rigorosa curadoria</p>
                            </div>
                        </div>
                        <div class="mission-stat">
                            <div class="stat-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <h3>Impacto Positivo</h3>
                                <p>Cada compra contribui para um planeta mais verde</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mission-image">
                    <img src="https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Sustentabilidade">
                </div>
            </div>
        </div>
    </section>

    <!-- Nossa Equipe -->
    <section class="team-section">
        <div class="container-sobre">
            <h2>Nossa Equipe</h2>
            <p class="team-subtitle">Conheça as pessoas por trás do Solum.</p>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="assets/sobre-fotos/caio2.jpeg" alt="Caio Mendes da Silva">
                    </div>
                    <div class="member-info">
                        <h3>Caio Mendes da Silva</h3>
                        <p class="member-role">Análista</p>
                        <!-- <p class="member-description">Especialista em sustentabilidade com 10 anos de experiência. Responsável pela visão estratégica e parcerias sustentáveis do marketplace.</p> -->
                        <div class="member-socials">
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="https://www.instagram.com/foxyyblink/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-photo">
                        <img src="assets/sobre-fotos/madu.jpeg" alt="msoumes">
                    </div>
                    <div class="member-info">
                        <h3>msoumes</h3>
                        <p class="member-role">Banco de Dados</p>
                        <!-- <p class="member-description">Desenvolvedor full-stack apaixonado por tecnologia verde. Lidera o desenvolvimento da plataforma e inovações tecnológicas.</p> -->
                        <div class="member-socials">
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="https://www.instagram.com/m.soumes/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-photo">
                        <img src="assets/sobre-fotos/malu.jpeg" alt="Malu">
                    </div>
                    <div class="member-info">
                        <h3>Maria Luiza</h3>
                        <p class="member-role">Página Web</p>
                        <!-- <p class="member-description">Especialista em marketing digital sustentável. Responsável por conectar marcas eco-friendly com consumidores conscientes.</p> -->
                        <div class="member-socials">
                            <a href="https://github.com/maluvsouza"><i class="fab fa-github"></i></a>
                            <a href="https://www.instagram.com/maluv.souza/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-photo">
                        <img src="assets/sobre-fotos/dibilowas.jpg" alt="dibi">
                    </div>
                    <div class="member-info">
                        <h3>dibilowas</h3>
                        <p class="member-role">Página Web</p>
                        <!-- <p class="member-description">Biólogo e consultor em sustentabilidade. Garante que todos os produtos atendam aos mais altos padrões ecológicos.</p> -->
                        <div class="member-socials">
                            <a href="https://github.com/dibilowass"><i class="fab fa-github"></i></a>
                            <a href="https://www.instagram.com/dibilowass/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <!-- <div class="team-member">
                    <div class="member-photo">
                        <img src="https://images.pexels.com/photos/3763188/pexels-photo-3763188.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Laura Costa">
                    </div>
                    <div class="member-info">
                        <h3>Laura Costa</h3>
                        <p class="member-role">UX/UI Designer</p>
                        <p class="member-description">Designer focada em experiências sustentáveis. Cria interfaces intuitivas que promovem escolhas conscientes.</p>
                        <div class="member-socials">
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://images.pexels.com/photos/2381069/pexels-photo-2381069.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Pedro Oliveira">
                    </div>
                    <div class="member-info">
                        <h3>Pedro Oliveira</h3>
                        <p class="member-role">Head de Operações</p>
                        <p class="member-description">Especialista em logística sustentável. Garante que toda a operação siga práticas ambientalmente responsáveis.</p>
                        <div class="member-socials">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>

    <!-- Nossos Valores -->
    <section class="values-section">
        <div class="container-sobre">
            <h2>Nossos Valores</h2>
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Transparência</h3>
                    <p>Informações claras sobre origem, produção e impacto ambiental de cada produto</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Comunidade</h3>
                    <p>Apoiamos pequenos produtores e empreendedores comprometidos com a sustentabilidade</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Qualidade</h3>
                    <p>Rigorosos critérios de seleção garantem produtos de alta qualidade e certificação</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Impacto Global</h3>
                    <p>Cada venda contribui para projetos ambientais e comunidades sustentáveis</p>
                </div>
            </div>
        </div>
    </section>

<?php require_once("./utils/footer.php") ?>