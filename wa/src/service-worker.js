importScripts("https://js.pusher.com/beams/service-worker.js");
<script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>

<script>
  const beamsClient = new PusherPushNotifications.Client({
    instanceId: '39da049e-3c77-4499-a2bb-cee86bcc5609',
  });

  beamsClient.start()
    .then(() => beamsClient.addDeviceInterest('hello'))
    .then(() => console.log('Successfully registered and subscribed!'))
    .catch(console.error);
</script>