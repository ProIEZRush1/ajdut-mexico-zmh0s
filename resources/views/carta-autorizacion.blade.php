<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "DejaVu Sans", sans-serif;
            color: #1f2937;
            font-size: 12.5px;
            line-height: 1.6;
        }
        .sheet { padding: 0 0 40px 0; }
        .header {
            background: #244543;
            color: #ffffff;
            padding: 26px 48px 22px 48px;
        }
        .brand-row { width: 100%; }
        .logo {
            width: 46px; height: 46px;
            background: #2e6862;
            border-radius: 12px;
            color: #ffffff;
            font-family: "Times New Roman", serif;
            font-size: 30px; font-weight: bold;
            text-align: center;
            line-height: 46px;
            display: inline-block;
            vertical-align: middle;
        }
        .brand-name {
            display: inline-block;
            vertical-align: middle;
            padding-left: 14px;
        }
        .brand-name .t1 { font-family: "Times New Roman", serif; font-size: 21px; font-weight: bold; letter-spacing: .3px; }
        .brand-name .t2 { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #cfa049; margin-top: 2px; }
        .accent { height: 4px; background: #bd5836; }
        .body { padding: 34px 48px 0 48px; }
        .doc-title {
            font-family: "Times New Roman", serif;
            font-size: 22px;
            font-weight: bold;
            color: #244543;
            margin: 0 0 4px 0;
        }
        .doc-sub { color: #6b7280; font-size: 11px; margin-bottom: 22px; }
        .meta-box {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 24px;
        }
        .meta-box td { padding: 10px 16px; font-size: 11.5px; }
        .meta-box .label { color: #6b7280; text-transform: uppercase; font-size: 9.5px; letter-spacing: 1px; }
        .meta-box .val { color: #111827; font-weight: bold; font-size: 14px; }
        .legal { text-align: justify; color: #374151; margin-bottom: 8px; }
        .legal strong { color: #244543; }
        .amount-inline { color: #bd5836; font-weight: bold; }
        .sig-wrap { margin-top: 30px; }
        .sig-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 18px 10px 18px;
            width: 320px;
        }
        .sig-img { height: 90px; }
        .sig-line { border-top: 1px solid #9ca3af; margin-top: 4px; padding-top: 6px; }
        .sig-name { font-weight: bold; color: #111827; font-size: 13px; }
        .sig-role { color: #6b7280; font-size: 10px; }
        .footer {
            margin-top: 36px;
            padding: 16px 48px 0 48px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 9.5px;
        }
        .mitzva { font-family: "Times New Roman", serif; font-style: italic; color: #2e6862; font-size: 13px; }
    </style>
</head>
<body>
<div class="sheet">
    <div class="header">
        <table class="brand-row"><tr>
            <td style="width:60px;"><span class="logo">A</span></td>
            <td class="brand-name">
                <div class="t1">AJDUT México</div>
                <div class="t2">Plataforma de Donaciones</div>
            </td>
            <td style="text-align:right; vertical-align:middle; color:#cfa049; font-size:10px; letter-spacing:1px;">
                FOLIO<br><span style="color:#ffffff; font-size:14px; font-weight:bold; letter-spacing:0;">{{ $folio }}</span>
            </td>
        </tr></table>
    </div>
    <div class="accent"></div>

    <div class="body">
        <div class="doc-title">Carta de Autorización de Cargo a Tarjeta</div>
        <div class="doc-sub">Emitida el {{ $fecha }}</div>

        <table class="meta-box"><tr>
            <td style="width:50%; border-right:1px solid #e5e7eb;">
                <div class="label">Monto autorizado</div>
                <div class="val">{{ $monto }}</div>
            </td>
            <td style="width:50%;">
                <div class="label">Frecuencia</div>
                <div class="val">{{ $frecuencia }}</div>
            </td>
        </tr></table>

        <p class="legal">
            Por medio de la presente, yo, <strong>{{ $firmante }}</strong>, autorizo expresa y voluntariamente
            a <strong>AJDUT México</strong> a realizar el cargo a mi tarjeta por la cantidad de
            <span class="amount-inline">{{ $monto }}</span>, con una frecuencia de <strong>{{ $frecuencia }}</strong>.
        </p>
        <p class="legal">
            Entiendo que esta autorización podrá cancelarse en cualquier momento desde mi Portal del Donador,
            y que AJDUT México resguardará este documento como respaldo del cargo realizado. Reconozco que mi
            aportación se destina al apoyo alimentario de familias de la comunidad mediante tarjetas para despensa.
        </p>

        @if(!empty($donador_email))
        <p class="legal" style="color:#6b7280; font-size:11px;">
            Donante: <strong>{{ $firmante }}</strong> &middot; {{ $donador_email }}
        </p>
        @endif

        <div class="sig-wrap">
            <div class="sig-box">
                @if(!empty($firma_img))
                    <img class="sig-img" src="{{ $firma_img }}" alt="Firma">
                @else
                    <div style="height:90px;"></div>
                @endif
                <div class="sig-line">
                    <div class="sig-name">{{ $firmante }}</div>
                    <div class="sig-role">Firma del donante &middot; {{ $fecha }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <table style="width:100%;"><tr>
            <td><span class="mitzva">Cada aportación es una mitzvá</span></td>
            <td style="text-align:right;">
                AJDUT México &middot; Documento generado automáticamente como respaldo de la autorización de cargo.
            </td>
        </tr></table>
    </div>
</div>
</body>
</html>
