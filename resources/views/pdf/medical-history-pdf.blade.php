<style>
    .clearfix:after {
    content: "";
    display: table;
    clear: both;
    }
    a {
    color: #0087C3;
    text-decoration: none;
    }
    body {
    position: relative;
    width: 21cm;
    height: 29.7cm;
    margin: 0 auto;
    color: #555555;
    background: #FFFFFF;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #AAAAAA;
    }
    #logo {
    float: left;
    margin-top: 8px;
    }
    #logo img {
    height: 70px;
    }
    #company {
    float: right;
    text-align: right;
    }
    #details {
    margin-bottom: 50px;
    }
    #client {
    padding-left: 6px;
    border-left: 6px solid #007d06;
    float: left;
    }
    #client .to {
    color: #777777;
    }
    h2.name {
    font-size: 1.4em;
    font-weight: normal;
    margin: 0;
    }
    #invoice {
    float: right;
    text-align: right;
    }
    #invoice h1 {
    color: #007d06;
    font-size: 2.4em;
    line-height: 1em;
    font-weight: normal;
    margin: 0  0 10px 0;
    }
    #invoice .date {
    font-size: 1.1em;
    color: #777777;
    }
    table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    }
    table th,
    table td {
    padding: 20px;
    background: #EEEEEE;
    text-align: center;
    border-bottom: 1px solid #FFFFFF;
    }
    table th {
    white-space: nowrap;
    font-weight: normal;
    }
    table td {
    text-align: right;
    }
    table td h3{
    color: #007d06;
    font-size: 1.2em;
    font-weight: normal;
    margin: 0 0 0.2em 0;
    }
    table .no {
    color: #FFFFFF;
    font-size: 1.6em;
    background: #007d06;
    }
    table .desc {
    text-align: left;
    }
    table .unit {
    background: #DDDDDD;
    }
    table .qty {
    }
    table .total {
    background: #57B223;
    color: #FFFFFF;
    }
    table td.unit,
    table td.qty,
    table td.total {
    font-size: 1.2em;
    }
    table tbody tr:last-child td {
    border: none;
    }
    table tfoot td {
    padding: 10px 20px;
    background: #FFFFFF;
    border-bottom: none;
    font-size: 1.2em;
    white-space: nowrap;
    border-top: 1px solid #AAAAAA;
    }
    table tfoot tr:first-child td {
    border-top: none;
    }
    table tfoot tr:last-child td {
    color: #57B223;
    font-size: 1.4em;
    border-top: 1px solid #57B223;

    }
    table tfoot tr td:first-child {
    border: none;
    }

    footer {
    color: #777777;
    width: 100%;
    height: 30px;
    position: absolute;
    bottom: 0;
    border-top: 1px solid #AAAAAA;
    padding: 8px 0;
    text-align: center;
    }
</style>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ public_path('assets/img/unlab_logo.png') }}" alt="Unlab Medical Clinic">
      </div>
      <div id="company">
        <h2 class="name">Unlab Medical Clinic</h2>
        <div>P & P, Apacible St, Ermita, Manila, 1000 Metro Manila</div>
        <div><a href="mailto:unlabmedicalclinic@gmail.com">unlabmedicalclinic@gmail.com</a></div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">Issued to:</div>
          <h2 class="name">{{ $patient->fullname }}</h2>
          <div class="address">{{ $patient->address }}</div>
          <div class="email"><a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a></div>
        </div>
        <div id="invoice" style="margin-top: 5px;">
          <h1>Medical Record</h1>
          <div class="date">Date of Issued: {{ $medical_record->date_issued }} </div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no"></th>
            <th class="desc">DESCRIPTION</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="no">Diagnosis</td>
            <td class="desc">
                <h3>{{ $medical_record->diagnosis }}</h3>
            </td>
          </tr>
          <tr>
            <td class="no">Symptoms</td>
            <td class="desc">
                {{ $medical_record->symptoms }}
            </td>
          </tr>
          <tr>
            <td class="no">Findings</td>
            <td class="desc">
                {{ $medical_record->findings }}
            </td>
          </tr>
          <tr>
            <td class="no">Medication</td>
            <td class="desc">
                {{ $medical_record->medications }}
            </td>
          </tr>
          <tr>
            <td class="no">Treatment Notes</td>
            <td class="desc">
                {{ $medical_record->treatment_notes }}
            </td>
          </tr>
          <tr>
            <td class="no">Next Appointment</td>
            <td class="desc">
                <h3>{{ $medical_record->next_appointment ?? 'N/A' }}</h3>
            </td>
          </tr>
        </tbody>
      </table>
      </div>
    </main>
    <footer>
    </footer>
  </body>
</html>