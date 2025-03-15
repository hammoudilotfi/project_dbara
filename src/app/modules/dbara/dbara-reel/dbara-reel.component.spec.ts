import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DbaraReelComponent } from './dbara-reel.component';

describe('DbaraReelComponent', () => {
  let component: DbaraReelComponent;
  let fixture: ComponentFixture<DbaraReelComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [DbaraReelComponent]
    });
    fixture = TestBed.createComponent(DbaraReelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
