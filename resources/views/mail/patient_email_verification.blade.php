<body style="margin: 30px auto; font-family:Arial, Helvetica, sans-serif;">
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <table style="background-color: #f6f7fb; width: 100%">
                    <tbody>
                        <tr>
                            <td>
                                <table style="width: 650px; margin: 0 auto; margin-bottom: 10px; margin-top: 30px;">
                                    <tbody>
                                        <tr>
                                            <td><img src="{{asset('assets/img/unlab_logo.png')}}" alt="Unlab Medical Clinic" style="width: 80px;"></td>
                                            <td style="text-align: right; color:#999"><span>Close to you, Far from Normal</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
                                    <tbody>
                                        <tr>
                                            <td style="padding: 30px">
                                                <p>Hi {{ $patient->first_name }}! <br><br>

                                                Please click the button below to verify your email address. <br><br>

                                                <button>
                                                    <a href="{{ $url }}" target="_blank" rel="noopener">
                                                        Verify Email Address
                                                    </a>
                                                </button> <br><br>

                                                If you did not create an account, no further action is required. <br><br>

                                                Regards, <br>
                                                Unlab Medical Clinic </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="width: 650px; margin: 0 auto; margin-top: 30px">
                                    <tbody>
                                        <tr style="text-align: center">
                                            <td>
                                                <p style="color: #999; margin-bottom: 0">P & P, Apacible St, Ermita, Manila, 1000 Metro Manila</p>
                                                <p style="color: #999; margin-bottom: 0">Powered By Pasiyente</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>