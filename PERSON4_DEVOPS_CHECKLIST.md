# 🔧 Person 4: DevOps Engineer

**Role**: Infrastructure, real-time features, deployment, storage, notifications  
**Duration**: Tuần 1-4  
**Dependencies**: Backend API (Person 1), AI engine (Person 3)  

---

## 📅 TUẦN 1: Storage & File Management

### Task 1.1: Local File Storage Setup
- [ ] Create storage directories:
  ```bash
  mkdir -p storage/app/uploads/requests
  mkdir -p storage/app/uploads/results
  mkdir -p storage/app/uploads/temp
  
  chmod 755 storage/app/uploads/
  chmod 755 storage/logs/
  ```

- [ ] Configure `.env` for storage:
  ```
  FILESYSTEM_DISK=local
  APP_URL=http://localhost:8000
  ```

- [ ] Create `config/filesystems.php` disk:
  ```php
  'disks' => [
    'local' => [
      'driver' => 'local',
      'root' => storage_path('app'),
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],
    'public' => [
      'driver' => 'local',
      'root' => storage_path('app/public'),
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],
  ]
  ```

- [ ] Link storage:
  ```bash
  php artisan storage:link
  ```

---

### Task 1.2: Image Upload Handler
- [ ] Create `app/Services/ImageUploadService.php`:
  ```php
  class ImageUploadService {
    public function uploadRequestImage(
      UploadedFile $file,
      int $idYeuCau
    ): string {
      // Validate
      $this->validateFile($file);
      
      // Generate unique name
      $name = $this->generateFileName($file);
      
      // Store
      $path = $file->storeAs(
        "requests/$idYeuCau",
        $name,
        'local'
      );
      
      // Resize/compress (optional, week 1)
      $this->compressImage($path);
      
      return $path;
    }
    
    public function uploadResultImage(...) { /* similar */ }
    
    private function validateFile(UploadedFile $file) {
      // Check: MIME type, size, extension
      if (!in_array($file->getClientMimeType(), 
          ['image/jpeg', 'image/png'])) {
        throw new \Exception("Invalid file type");
      }
      if ($file->getSize() > 5 * 1024 * 1024) {  // 5MB
        throw new \Exception("File too large");
      }
    }
    
    private function compressImage($path) {
      // Optional: use Intervention Image
      // Reduce quality to 80%, resize if > 4000px
    }
    
    private function generateFileName($file): string {
      $ext = $file->getClientOriginalExtension();
      return md5(time() . rand()) . '.' . $ext;
    }
  }
  ```

- [ ] Integrate into controller (Person 1 will call):
  ```php
  public function store(Request $request) {
    $images = [];
    foreach ($request->file('hinh_anh', []) as $file) {
      $path = \ImageUploadService::uploadRequestImage(
        $file,
        $request->id_yeu_cau
      );
      $images[] = $path;
    }
    // Save to DB
  }
  ```

---

### Task 1.3: Image Processing (Optional)
- [ ] Install `intervention/image`:
  ```bash
  composer require intervention/image
  ```

- [ ] Compress/resize:
  ```php
  // In ImageUploadService::compressImage()
  $image = Image::make(storage_path("app/$path"));
  $image->resize(4000, 4000, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upSize();
  });
  $image->encode('jpeg', 80);  // 80% quality
  $image->save();
  ```

**Acceptance Criteria:**
- Images stored successfully ✓
- File validation working ✓
- Generated path correct ✓

---

### Task 1.4: Cleanup Cron Job
- [ ] Create `app/Console/Commands/CleanupOldUploads.php`:
  - [ ] Delete temp files > 1 hour old
  - [ ] Delete orphaned images (request deleted but files remain)
  - [ ] Log cleanup results

- [ ] Register in Kernel:
  ```php
  $schedule->command('app:cleanup-uploads')
           ->hourly()
           ->withoutOverlapping();
  ```

**Acceptance Criteria:**
- Old files deleted ✓
- Orphaned files cleaned ✓

---

## 📅 TUẦN 2: Push Notifications & Real-time

### Task 2.1: Firebase Cloud Messaging (FCM) Setup
- [ ] Create Firebase project:
  - [ ] Go to consolefirebase.google.com
  - [ ] Create new project (name: `sos-system`)
  - [ ] Enable Cloud Messaging
  - [ ] Download service account JSON key
  - [ ] Place in `config/firebase-credentials.json`

- [ ] Install Firebase package:
  ```bash
  composer require kreait/firebase-php
  ```

- [ ] Create `app/Services/FirebaseService.php`:
  ```php
  class FirebaseService {
    private $messaging;
    
    public function __construct() {
      $factory = (new Factory())
        ->withServiceAccount(
          config_path('firebase-credentials.json')
        );
      $this->messaging = $factory->createMessaging();
    }
    
    public function sendNotification(
      string $deviceToken,
      string $title,
      string $body,
      array $data = []
    ): void {
      $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(
          Notification::create($title, $body)
        )
        ->withData($data);
      
      $this->messaging->send($message);
    }
    
    public function sendToMultiple(
      array $deviceTokens,
      string $title,
      string $body,
      array $data = []
    ): void {
      foreach ($deviceTokens as $token) {
        $this->sendNotification($token, $title, $body, $data);
      }
    }
  }
  ```

- [ ] Create database migration for storing tokens:
  ```bash
  php artisan make:migration create_fcm_tokens_table
  ```
  ```php
  Schema::create('fcm_tokens', function (Blueprint $table) {
    $table->id();
    $table->string('user_type');  // 'admin', 'team', 'user'
    $table->unsignedBigInteger('user_id');
    $table->string('device_token');
    $table->string('device_name')->nullable();
    $table->timestamps();
    $table->unique(['user_type', 'user_id', 'device_token']);
  });
  ```

- [ ] API endpoints for token management:
  - [ ] POST `/api/fcm/register-device` - store token
  - [ ] DELETE `/api/fcm/unregister-device` - remove token

**Acceptance Criteria:**
- Firebase project created ✓
- Credentials secured ✓
- Token storage working ✓

---

### Task 2.2: Notification Types & Templates
- [ ] Create `app/Notifications/` directory structure:
  ```
  Notifications/
  ├── NewTaskAssignedNotification.php
  ├── RequestCompletedNotification.php
  ├── RatingRequestNotification.php
  ├── AlertCriticalWaitingNotification.php
  └── ...
  ```

- [ ] Each notification class:
  ```php
  class NewTaskAssignedNotification {
    public function __construct(
      private PhanCongCuuHo $assignment
    ) {}
    
    public function toFirebase(): array {
      return [
        'title' => 'Nhiệm vụ mới được giao',
        'body' => 'Bạn có 1 nhiệm vụ cứu hộ mới',
        'data' => [
          'type' => 'task_assigned',
          'id_phan_cong' => $this->assignment->id_phan_cong,
          'id_request' => $this->assignment->id_yeu_cau,
        ]
      ];
    }
  }
  ```

- [ ] Notification types:
  - [ ] `NewTaskAssignedNotification` - team receives assignment
  - [ ] `RequestCompletedNotification` - user gets rating link
  - [ ] `AlertCriticalWaitingNotification` - admin alert (CRITICAL > 2h)
  - [ ] `EscalationAlertNotification` - admin alert (HIGH > 4h)
  - [ ] `TeamLocationArrival` - user notified when team nearby

**Acceptance Criteria:**
- Notification classes created ✓
- Templates defined ✓

---

### Task 2.3: Dispatch Notifications from Jobs
- [ ] Create `app/Jobs/SendNotificationJob.php`:
  ```php
  class SendNotificationJob implements ShouldQueue {
    public function __construct(
      private string $notificationType,
      private int $userId,
      private string $userType,  // 'admin', 'team', 'user'
      private array $data = []
    ) {}
    
    public function handle() {
      $tokens = FcmToken::where('user_type', $this->userType)
        ->where('user_id', $this->userId)
        ->pluck('device_token')
        ->toArray();
      
      if (empty($tokens)) return;
      
      $notification = $this->buildNotification();
      
      foreach ($tokens as $token) {
        FirebaseService::sendNotification(
          $token,
          $notification['title'],
          $notification['body'],
          $notification['data']
        );
      }
    }
    
    private function buildNotification(): array {
      return match($this->notificationType) {
        'task_assigned' => new NewTaskAssignedNotification(...),
        'request_completed' => new RequestCompletedNotification(...),
        // ...
      };
    }
  }
  ```

- [ ] Dispatch from backend controllers (Person 1 calls):
  ```php
  // When assignment created
  SendNotificationJob::dispatch(
    'task_assigned',
    $team->id,
    'team',
    ['id_phan_cong' => $phanCong->id]
  );
  ```

**Acceptance Criteria:**
- Notifications sent via FCM ✓
- Delivered to correct devices ✓

---

### Task 2.4: Real-time GPS Updates (WebSocket - Optional)
- [ ] If budget/time: skip, use polling instead (60s interval)
- [ ] If implementing WebSocket:
  - [ ] Install `laravel-websockets`:
    ```bash
    composer require beyondcode/laravel-websockets
    ```
  - [ ] Configure channels in `config/broadcasting.php`
  - [ ] Setup channel auth (team can only broadcast their location)
  - [ ] Admin subscribes to team location channels
  - [ ] Frontend WebSocket listeners for real-time map updates

**Note**: WebSocket complex, consider polling first (week 1-3 works fine)

**Acceptance Criteria:**
- GPS updates visible in real-time ✓
- OR polling every 60s (simpler) ✓

---

## 📅 TUẦN 3: Queue & Caching

### Task 3.1: Laravel Queue Configuration
- [ ] Choose queue driver in `.env`:
  ```
  QUEUE_CONNECTION=database  # or redis
  ```

- [ ] If Redis:
  ```bash
  composer require predis/predis
  # Configure in .env:
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```

- [ ] Setup queue worker in production:
  ```bash
  # Supervisor config for production
  php artisan queue:work --tries=3 --delay=0
  ```

- [ ] Create `config/queue.php` connections:
  ```php
  'connections' => [
    'database' => [
      'driver' => 'database',
      'table' => 'jobs',
      'queue' => 'default',
      'retry_after' => 90,
    ],
    'redis' => [
      'driver' => 'redis',
      'connection' => 'default',
      'queue' => env('REDIS_QUEUE', 'default'),
      'retry_after' => 90,
    ],
  ]
  ```

- [ ] Create jobs table:
  ```bash
  php artisan queue:table
  php artisan migrate
  ```

**Acceptance Criteria:**
- Queue driver selected ✓
- Database/Redis configured ✓
- Queue table created ✓

---

### Task 3.2: Redis Caching Configuration
- [ ] Install Redis:
  ```bash
  # Windows: download from memurai.com or use Docker
  # Linux: apt-get install redis-server
  ```

- [ ] Configure `.env`:
  ```
  CACHE_DRIVER=redis
  CACHE_HOST=127.0.0.1
  CACHE_PORT=6379
  ```

- [ ] Create cache store in `config/cache.php`:
  ```php
  'stores' => [
    'redis' => [
      'driver' => 'redis',
      'connection' => 'cache',
      'prefix' => 'sos_cache',
    ],
  ]
  ```

- [ ] Usage in code (Person 1 will use):
  ```php
  // Cache team list for 5 minutes
  $teams = Cache::remember('teams_list', 300, function () {
    return DoiCuuHo::all();
  });
  
  // Invalidate on update
  Cache::forget('teams_list');
  ```

- [ ] Key cache points:
  - [ ] `loai_su_co` list (static data, 1 day)
  - [ ] `doi_cuu_ho` list (5 min)
  - [ ] `heatmap_data` (5 min)
  - [ ] `trong_so_phan_loai` weights (1 day)

**Acceptance Criteria:**
- Redis running ✓
- Cache configured ✓

---

### Task 3.3: Rate Limiting
- [ ] Configure in `.env`:
  ```
  RATE_LIMITING=true
  ```

- [ ] Create `app/Http/Middleware/ThrottleRequests.php`:
  ```php
  // Limit endpoints:
  // - Gửi yêu cầu: 3 per user per hour
  // - GPS update: 60 per team per hour
  // - General API: 1000 per IP per hour
  ```

- [ ] Apply middleware to routes:
  ```php
  Route::middleware('throttle:3,60')->group(function () {
    Route::post('user/yeu-cau', ...);  // 3 per minute
  });
  ```

**Acceptance Criteria:**
- Rate limiting enforced ✓
- Returns 429 when exceeded ✓

---

## 📅 TUẦN 4: Deployment & Monitoring

### Task 4.1: Docker Containerization
- [ ] Create `Dockerfile`:
  ```dockerfile
  FROM php:8.1-fpm
  
  RUN apt-get update && apt-get install -y \
    libpq-dev \
    mysql-client \
    redis-tools \
    && docker-php-ext-install pdo_mysql pdo_pgsql
  
  WORKDIR /app
  COPY . .
  
  RUN composer install --no-dev --optimize-autoloader
  
  EXPOSE 9000
  CMD ["php-fpm"]
  ```

- [ ] Create `docker-compose.yml`:
  ```yaml
  version: '3.8'
  services:
    app:
      build: .
      ports:
        - "9000:9000"
      volumes:
        - .:/app
      environment:
        - DB_HOST=mysql
        - REDIS_HOST=redis
    
    mysql:
      image: mysql:8.0
      environment:
        - MYSQL_DATABASE=${DB_DATABASE}
        - MYSQL_ROOT_PASSWORD=${DB_PASSWORD}
      ports:
        - "3306:3306"
    
    redis:
      image: redis:7-alpine
      ports:
        - "6379:6379"
    
    nginx:
      image: nginx:alpine
      ports:
        - "80:80"
      volumes:
        - ./docker/nginx.conf:/etc/nginx/nginx.conf
        - .:/app
  ```

- [ ] Start:
  ```bash
  docker-compose up -d
  ```

**Acceptance Criteria:**
- Docker builds ✓
- Services run ✓

---

### Task 4.2: CI/CD Pipeline (GitHub Actions)
- [ ] Create `.github/workflows/laravel.yml`:
  ```yaml
  name: Laravel Tests
  
  on: [push, pull_request]
  
  jobs:
    laravel-tests:
      runs-on: ubuntu-latest
      
      services:
        mysql:
          image: mysql:8.0
          env:
            MYSQL_DATABASE: sos_test
            MYSQL_ROOT_PASSWORD: root
        
        redis:
          image: redis:7-alpine
      
      steps:
        - uses: actions/checkout@v3
        
        - uses: php-actions/setup-php@v3
          with:
            php-version: '8.1'
            extensions: pdo, pdo_mysql, redis
        
        - name: Install dependencies
          run: composer install
        
        - name: Run migrations
          run: php artisan migrate --env=testing
        
        - name: Run tests
          run: php artisan test
        
        - name: Upload coverage
          run: |
            composer require phpunit/phpcov
            php artisan test --coverage
  ```

- [ ] On every push:
  - [ ] Run tests
  - [ ] Check coverage > 80%
  - [ ] Linting (PHPStan)
  - [ ] Build Docker image
  - [ ] Deploy if tests pass

**Acceptance Criteria:**
- CI/CD pipeline working ✓
- Tests run automatically ✓

---

### Task 4.3: Monitoring & Logging
- [ ] Configure logging in `.env`:
  ```
  LOG_CHANNEL=stack
  LOG_LEVEL=debug
  ```

- [ ] Setup in `config/logging.php`:
  ```php
  'stack' => [
    'channels' => ['single', 'slack'],  // also log to Slack
  ]
  ```

- [ ] Install Laravel Telescope (development):
  ```bash
  composer require laravel/telescope
  php artisan telescope:install
  php artisan migrate
  ```
  - [ ] Access at `/telescope` to debug requests/queries

- [ ] For production, use monitoring service:
  - [ ] Option 1: Sentry (error tracking)
    ```bash
    composer require sentry/sentry-laravel
    ```
  - [ ] Option 2: Datadog (full monitoring)
  - [ ] Option 3: New Relic

**Acceptance Criteria:**
- Logging configured ✓
- Telescope accessible in dev ✓
- Errors captured ✓

---

### Task 4.4: Production Deployment Checklist
- [ ] Environment setup:
  - [ ] [ ] `APP_ENV=production`
  - [ ] [ ] `APP_DEBUG=false`
  - [ ] [ ] `CACHE_DRIVER=redis`
  - [ ] [ ] `QUEUE_CONNECTION=redis` or `database`
  - [ ] [ ] `SESSION_DRIVER=redis`
  - [ ] [ ] SSL certificate installed
  - [ ] [ ] Environment variables set securely

- [ ] Database:
  - [ ] [ ] Run migrations: `php artisan migrate --force`
  - [ ] [ ] Seed production data (careful!)
  - [ ] [ ] Backup before deploy
  - [ ] [ ] Setup automated backups (daily)

- [ ] Performance:
  - [ ] [ ] Config cache: `php artisan config:cache`
  - [ ] [ ] Route cache: `php artisan route:cache`
  - [ ] [ ] View cache: `php artisan view:cache`
  - [ ] [ ] Asset minification

- [ ] Security:
  - [ ] [ ] Setup CORS properly
  - [ ] [ ] Rate limiting enabled
  - [ ] [ ] API key rotation
  - [ ] [ ] Database password strong
  - [ ] [ ] File permissions: `storage/` writable only by app

- [ ] Monitoring:
  - [ ] [ ] Error tracking setup
  - [ ] [ ] Performance monitoring
  - [ ] [ ] Uptime monitoring
  - [ ] [ ] Alert rules configured

**Acceptance Criteria:**
- Production ready ✓
- Secure ✓
- Monitored ✓

---

### Task 4.5: Disaster Recovery & Backups
- [ ] Database backups:
  ```bash
  # Create cron job (daily 3 AM)
  0 3 * * * mysqldump -u root -p$DB_PASSWORD $DB_NAME > /backups/db-$(date +\%Y\%m\%d).sql
  ```

- [ ] File backups:
  ```bash
  # Backup uploads daily
  0 4 * * * tar -czf /backups/uploads-$(date +\%Y\%m\%d).tar.gz /app/storage/app/uploads/
  ```

- [ ] Retention policy:
  - [ ] Keep daily backups for 30 days
  - [ ] Keep weekly backups for 90 days
  - [ ] Keep monthly backups for 1 year

- [ ] Test restore monthly:
  - [ ] Verify backups are valid
  - [ ] Document restore procedure

**Acceptance Criteria:**
- Backups automated ✓
- Tested restore ✓

---

## ✅ Definition of Done (Week 4)

- [ ] File upload working + images stored
- [ ] FCM notifications sending
- [ ] Real-time GPS (WebSocket or polling)
- [ ] Queue system running (jobs processed)
- [ ] Redis caching active
- [ ] Rate limiting enforced
- [ ] Docker container built
- [ ] CI/CD pipeline working
- [ ] Monitoring active
- [ ] Production deployment checklist complete
- [ ] Backups automated & tested

---

## 🛠️ Troubleshooting

**Queue not processing?**
```bash
# Start queue worker
php artisan queue:work

# Clear failed jobs
php artisan queue:failed
php artisan queue:retry all
```

**Redis not connecting?**
```bash
# Check Redis is running
redis-cli ping  # should return PONG

# Check connection in Laravel
php artisan tinker
> Cache::put('test', 'value', 60)
> Cache::get('test')  // should return 'value'
```

**FCM token not working?**
```bash
# Verify Firebase credentials
php artisan tinker
> \App\Services\FirebaseService::class
> // Try sending test notification
```

---

**Last Updated**: 2026-03-31  
**Status**: Not Started  
☐ Week 1 ☐ Week 2 ☐ Week 3 ☐ Week 4 ☐ Complete
