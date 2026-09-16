<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>Ative sua conta</title>
</head>
<body style="margin:0; padding:0; background-color:#fbf7f2; color:#2e2927; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#fbf7f2;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px; background-color:#ffffff; border:1px solid rgba(82,59,49,.14);">
                    <tr>
                        <td style="padding:28px 36px; background-color:#332925;">
                            <p style="margin:0; color:#e6a59a; font-size:12px; font-weight:bold; letter-spacing:2px; text-transform:uppercase;">Agendamentos</p>
                            <p style="margin:12px 0 0; color:#fff9f3; font-family:Georgia, 'Times New Roman', serif; font-size:27px; line-height:1.2; font-weight:bold;">Seu cuidado começa aqui.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 36px 16px;">
                            <p style="margin:0 0 10px; color:#914646; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Ativação da conta</p>
                            <h1 style="margin:0; color:#2e2927; font-family:Georgia, 'Times New Roman', serif; font-size:30px; line-height:1.2; font-weight:bold;">Quase tudo pronto.</h1>
                            <p style="margin:16px 0 0; color:#766d68; font-size:16px; line-height:1.6;">Use o código abaixo para confirmar seu e-mail, ativar sua conta e começar a cuidar dos seus próximos momentos com a gente.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 36px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid rgba(184,92,91,.25); border-left:4px solid #b85c5b; background-color:#fbf7f2;">
                                <tr>
                                    <td align="center" style="padding:24px 22px;">
                                        <p style="margin:0 0 10px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Seu código de ativação</p>
                                        <p style="margin:0; color:#2e2927; font-size:34px; line-height:1.2; font-weight:bold; letter-spacing:8px;"><?= esc($code) ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 36px 36px;">
                            <p style="margin:0 0 16px; color:#2e2927; font-size:15px; font-weight:bold;">Detalhes do cadastro</p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top:1px solid rgba(82,59,49,.14);">
                                <tr>
                                    <td width="34%" style="padding:12px 10px 12px 0; color:#766d68; font-size:13px; border-bottom:1px solid rgba(82,59,49,.14);">Usuário</td>
                                    <td style="padding:12px 0; color:#2e2927; font-size:13px; border-bottom:1px solid rgba(82,59,49,.14); word-break:break-word;"><?= esc($user->username) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 10px 12px 0; color:#766d68; font-size:13px; border-bottom:1px solid rgba(82,59,49,.14);">Endereço IP</td>
                                    <td style="padding:12px 0; color:#2e2927; font-size:13px; border-bottom:1px solid rgba(82,59,49,.14); word-break:break-word;"><?= esc($ipAddress) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 10px 12px 0; color:#766d68; font-size:13px; line-height:1.5; border-bottom:1px solid rgba(82,59,49,.14);">Dispositivo</td>
                                    <td style="padding:12px 0; color:#2e2927; font-size:13px; line-height:1.5; border-bottom:1px solid rgba(82,59,49,.14); word-break:break-word;"><?= esc($userAgent) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 10px 0; color:#766d68; font-size:13px;">Data</td>
                                    <td style="padding:12px 0 0; color:#2e2927; font-size:13px; word-break:break-word;"><?= esc($date) ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 36px; background-color:#f1e7dc; border-top:1px solid rgba(82,59,49,.14);">
                            <p style="margin:0; color:#766d68; font-size:13px; line-height:1.6;">Se você não criou esta conta, ignore este e-mail. Nenhuma ação será realizada sem a confirmação do código.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:20px 0 0; color:#766d68; font-size:12px; line-height:1.5;">Beleza, bem-estar e tempo para você.</p>
            </td>
        </tr>
    </table>
</body>
</html>
