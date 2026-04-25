MULTI CELL — HandOff de Publicação

1. Importar multicell.sql no banco MySQL/MariaDB.
2. Extrair o conteúdo de public_html/ dentro da pasta public_html da hospedagem.
3. Editar app/config/config.php com host, banco, usuário e senha reais.
4. Atualizar WhatsApp, horário, endereço, mapa e links no painel ou direto em settings.
5. Acessar /admin/login com:
   E-mail: admin@multicell.local
   Senha: ChangeMe123!
6. Alterar a senha imediatamente em produção (nesta versão inicial, trocando o hash diretamente no banco).

Observações:
- Esta build evita uso da coluna reservada `key`.
- Não há debug público.
- O site funciona em modo fallback visual até o banco ser configurado.
- Para produção real, substitua os placeholders de WhatsApp e revise os textos.

Cron sugerido (resumo semanal por email · segunda 9h):
0 9 * * 1 /usr/bin/php /home/USUARIO/public_html/app/Console/WeeklyDigest.php >> /home/USUARIO/public_html/storage/logs/digest.log 2>&1
(adicione no Hostinger via hPanel → Avançado → Tarefas Cron)
