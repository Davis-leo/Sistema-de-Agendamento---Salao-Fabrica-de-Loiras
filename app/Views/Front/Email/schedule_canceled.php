<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Cancelado</title>
</head>
<?php
    $date = date_create($chosen_date);
    $months = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro',
    ];
    $formattedDate = $date
        ? $date->format('d') . ' de ' . $months[(int) $date->format('n')] . ' de ' . $date->format('Y') . ' às ' . $date->format('H:i')
        : $chosen_date;
?>
<body style="margin:0; padding:0; background-color:#fbf7f2; color:#2e2927; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#fbf7f2;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px; background-color:#ffffff; border:1px solid rgba(82,59,49,.14);">
                    <tr>
                        <td style="padding:28px 36px; background-color:#332925;">
                            <p style="margin:0; color:#e6a59a; font-size:12px; font-weight:bold; letter-spacing:2px; text-transform:uppercase;">Agendamentos</p>
                            <p style="margin:12px 0 0; color:#fff9f3; font-family:Georgia, 'Times New Roman', serif; font-size:27px; line-height:1.2; font-weight:bold;">O seu agendamento foi cancelado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 36px 16px;">
                            <p style="margin:0 0 10px; color:#914646; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Cancelamento de agendamento</p>
                            <h1 style="margin:0; color:#2e2927; font-family:Georgia, 'Times New Roman', serif; font-size:30px; line-height:1.2; font-weight:bold;">Agendamento cancelado.</h1>
                            <p style="margin:16px 0 0; color:#766d68; font-size:16px; line-height:1.6;">O seu horário foi cancelado e não está mais reservado. Confira os detalhes abaixo:</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 36px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid rgba(82,59,49,.14); border-left:4px solid #b85c5b; background-color:#fbf7f2;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <p style="margin:0 0 6px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Data e horário cancelados</p>
                                        <p style="margin:0; color:#2e2927; font-size:20px; line-height:1.4; font-weight:bold;"><?php echo $formattedDate; ?></p>
                                        <p style="margin:10px 0 0; color:#766d68; font-size:13px; line-height:1.5;">Cancelado em: <?php echo $when; ?></p>
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
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Serviço</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.45; font-weight:bold;"><?php echo $service; ?></p>
                                    </td>
                                    <td width="50%" valign="top" style="padding:0 0 24px 18px; border-bottom:1px solid rgba(82,59,49,.14);">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Unidade</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.45; font-weight:bold;"><?php echo $unit; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:24px 0 0;">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Profissional</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.5;"><?php echo $professional; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:24px 0 0;">
                                        <p style="margin:0 0 7px; color:#766d68; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">Endereço</p>
                                        <p style="margin:0; color:#2e2927; font-size:16px; line-height:1.5;"><?php echo $address; ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 36px; background-color:#f1e7dc; border-top:1px solid rgba(82,59,49,.14);">
                            <p style="margin:0; color:#766d68; font-size:13px; line-height:1.6;">Caso ainda queira realizar este atendimento, faça um novo agendamento pelo nosso canal de atendimento.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:20px 0 0; color:#766d68; font-size:12px; line-height:1.5;">Beleza, bem-estar e tempo para você.</p>
            </td>
        </tr>
    </table>
</body>
</html>