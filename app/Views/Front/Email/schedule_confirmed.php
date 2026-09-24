<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atendimento confirmado</title>
</head>
<body style="margin:0; padding:0; background-color:#fbf7f2; color:#2e2927; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#fbf7f2;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px; background-color:#ffffff; border:1px solid rgba(82,59,49,.14);">
                    <tr>
                        <td style="padding:28px 36px; background-color:#332925;">
                            <p style="margin:0; color:#e6a59a; font-size:12px; font-weight:bold; letter-spacing:2px; text-transform:uppercase;">Agendamentos</p>
                            <p style="margin:12px 0 0; color:#fff9f3; font-family:Georgia, 'Times New Roman', serif; font-size:27px; line-height:1.2; font-weight:bold;">Seu atendimento foi finalizado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 36px 16px;">
                            <p style="margin:0 0 10px; color:#914646; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Atendimento finalizado</p>
                            <h1 style="margin:0; color:#2e2927; font-family:Georgia, 'Times New Roman', serif; font-size:30px; line-height:1.2; font-weight:bold;">Obrigado pela preferência.</h1>
                            <p style="margin:16px 0 0; color:#766d68; font-size:16px; line-height:1.6;">Seu atendimento foi finalizado. Será um prazer receber você novamente em nosso espaço.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 36px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid rgba(184,92,91,.25); border-left:4px solid #b85c5b; background-color:#fbf7f2;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <p style="margin:0 0 6px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Data e horário</p>
                                        <p style="margin:0; color:#2e2927; font-size:20px; line-height:1.4; font-weight:bold;"><?php echo date('d/m/Y H:i', strtotime($chosen_date)); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 36px 36px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td width="50%" valign="top" style="padding:0 18px 24px 0; border-bottom:1px solid rgba(82,59,49,.14);">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Serviços</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.45; font-weight:bold;"><?php echo esc($service); ?></p>
                                    </td>
                                    <td width="50%" valign="top" style="padding:0 0 24px 18px; border-bottom:1px solid rgba(82,59,49,.14);">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Unidade</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.45; font-weight:bold;"><?php echo esc($unit); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:24px 0 0;">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Profissionais</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.5;"><?php echo esc($professional); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:24px 0 0;">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Endereço</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.5;"><?php echo esc($address); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 36px; background-color:#f1e7dc; border-top:1px solid rgba(82,59,49,.14);">
                            <p style="margin:0; color:#766d68; font-size:13px; line-height:1.6;">Quando quiser renovar esse momento, estaremos esperando por você. Agende novamente e venha nos visitar.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:20px 0 0; color:#766d68; font-size:12px; line-height:1.5;">Beleza, bem-estar e tempo para você.</p>
            </td>
        </tr>
    </table>
</body>
</html>
