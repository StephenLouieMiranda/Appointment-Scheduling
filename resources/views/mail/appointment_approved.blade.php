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
                                            <td><img src="{{ public_path('assets/img/unlab_logo.png') }}" alt="Unlab Medical Clinic" style="width: 80px;"></td>
                                            <td style="text-align: right; color:#999"><span>Close to you, Far from Normal</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
                                    <tbody>
                                        <tr>
                                            <td style="padding: 30px">
                                                <p>Hi {{ $appointment->patient->first_name }}! <br><br>

                                                I am delighted to inform you that your booked appointment on {{ $appointment->schedule }} has been approved. <br><br>

                                                Appointment Details: <br>
                                                Appointment Code: {{ $appointment->appointment_code }} <br>
                                                Schedule: {{ $appointment->schedule }} <br>
                                                Doctor: Dr. {{ $appointment->user->first_name }} {{ $appointment->user->last_name }} <br><br>

                                                Please ensure that you arrive on time, at least 5 to 10 minutes earlier before your appointment. If you have any questions or need further information,
                                                please feel free to contact us at unlabmedicalclinic@gmail.com. <br><br>

                                                Cheers! </p>
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